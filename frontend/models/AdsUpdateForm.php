<?php

namespace frontend\models;

use common\models\AdsCategory;
use common\models\Advertise;
use common\models\CategoryAds;
use common\models\Wallet;

/**
 * Ads form
 */
class AdsUpdateForm extends AdsForm {

	public $ads_id;
	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[ 'title', 'trim' ],
			//['title', 'required', 'message' => Yii::t('frontend', 'Missing title')],
			[ 'budget', 'required', 'message' => \Yii::t( 'frontend', 'Thiếu ngân sách dự chi' ) ],
			[ 'require', 'required', 'message' => \Yii::t( 'frontend', 'Thiếu Yêu cầu' ) ],
			[ [ 'title', 'require', 'message' ], 'string' ],
			[ 'age_max', 'integer', 'max' => 80 ],
			[ 'age_min', 'integer', 'min' => 18 ],
			[ [ 'ads_type', 'time_type', 'price_suggest' ], 'integer' ],
			[ [ 'age_max' ], 'compare', 'compareAttribute' => 'age_min', 'operator' => '>=', 'skipOnEmpty' => true ],
			[ [ 'images', 'location', 'age', 'category','ads_id' ], 'safe' ]
		];
	}
	/**
	 *
	 * @return boolean the saved model or null if saving fails
	 */
	public function update() {
		if ( $this->validate() ) {
			$model = Advertise::find()->where( [ 'id' => $this->ads_id, 'user_id' => $this->user_id ] )->one();
			if ( $model ) {
				$model->title       = $this->title;
				$model->content     = $this->require;
				$model->require     = $this->require;
				$model->message     = $this->message;
				$model->description = $this->message;
				$model->ads_type    = $this->ads_type;
				$model->time_type   = $this->time_type;
				if ( $this->category ) {
					$model->cat_id = $this->category[0];
				} else {
					$model->cat_id = 0;
				}

				$model->user_id = $this->user_id;
				$model->age_min = $this->age_min;
				$model->age_max = $this->age_max;

				$price_base = (int) \Yii::$app->keyStorage->get( 'config.price-basic', 50000 );
				if ( $this->price_suggest ) {
					if ( $this->price_suggest < $price_base ) {
						return 2; // Giá đặt nhỏ hơn giá quy định
					} else {
						$price_base = $this->price_suggest;
					}
				}
				$price = $this->getPriceUnit( $price_base );

				// Gán giá 1 lần share cho
				$model->price_on_share = $price;

				if ( $model->logs ) {
					$logs = json_decode($model->logs);
				} else {
					$logs = array();
				}

				$criteria = array(
					'location'      => $this->location,
					'category'      => $this->category,
					'age_min'       => $this->age_min,
					'age_max'       => $this->age_max,
					'age'           => $this->age,
					'budget'        => $this->budget,
					'price_suggest' => $this->price_suggest,
				);

				$logs[] = array(
					time() => $criteria
				);

				$model->logs = json_encode( $logs );
				$model->criteria = json_encode($criteria);
				// Số lần có thể share với ngân sách đưa ra
				$share = intval( $this->budget / $price );
				if ( $share <= 0 ) {
					return 3; // Không đủ lượt share
				}
				// Số tiền thực sự phải chi trong quảng cáo
				$realMoney = $share * $price;

				$model->budget = $realMoney;

				// Gán ngân sách
				$model->budget_remain = $realMoney;

				$wallet = Wallet::findOrCreate( $this->user_id );
				if ( $wallet->amount >= $model->budget ) {
					$wallet->amount -= $realMoney;
					$wallet->save();
				} else {
					return 4; // Out of money
				}

				$model->share  = $share;
				$model->status = Advertise::STATUS_PENDING;

				if ( $model->save( false ) ) {
					$primaryKey = $model->getPrimaryKey();

					$tags = $this->addCategories( $this->category, $this->ads_id, true );

					$this->transaction( $this->user_id, $this->budget, "Cập nhật quảng cáo " . $this->title );

					if ( $this->images ) {
						$this->createImages( $this->ads_id, $model );
					}

					$this->_ads = $model;

					return 1;
				} else {
					return 0; // Không save được
				}
			} else {
				return 0;
			}
		}

		return 0;
	}

}