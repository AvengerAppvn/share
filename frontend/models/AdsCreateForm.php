<?php

namespace frontend\models;

use common\models\AdsCategory;
use common\models\Advertise;
use common\models\CategoryAds;
use common\models\Wallet;

/**
 * Ads form
 */
class AdsCreateForm extends AdsForm {

	/**
	 *
	 * @return boolean the saved model or null if saving fails
	 * 1 Ok
	 * 2 Giá đặt nhỏ hơn giá quy định
	 * 4 // Out of money
	 */
	public function save() {
		if ( $this->validate() ) {
			$model              = new Advertise();
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
			$criteria =  array(
				'location'      => $this->location,
				'category'      => $this->category,
				'age_min'       => $this->age_min,
				'age_max'       => $this->age_max,
				'age'           => $this->age,
				'budget'        => $this->budget,
				'price_suggest' => $this->price_suggest,
			);
			$model->logs = json_encode( array(
				time() =>$criteria
			) );
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

			$model->share = $share;
			// Tạo lần đầu tiên, trạng thái là chờ xác nhận
			$model->status = Advertise::STATUS_PENDING;

			if ( $model->save( false ) ) {
				$primaryKey = $model->getPrimaryKey();

				$tags = $this->addCategories( $this->category, $primaryKey );

				$this->transaction( $this->user_id, $this->budget, "Chi quảng cáo " . $this->title );

				if ( $this->images ) {
					$this->createImages( $primaryKey, $model );
				}
				// 3 Khi có ads phù hợp
//                $message = array('en'=>'Bạn có quảng cáo phù hợp với chuyên môn của bạn');
//                //$options = array( "include_player_ids"=> ["a7d48d0a-fa11-4cf8-a412-fc3f56388ad2"],"data"=> array("ads_id"=> $model->id),);
//                $filters = array();
//                foreach ($tags as $tag){
//                    $filters[] = array("field" => "tag", "key" => $tag, "relation" => "=", "value" => "1");
//                }
//                $options = array( 'filters' => $filters,"data"=> array("type"=>3,"ads_id"=> $model->id),"buttons"=>[array("id"=> "1", "text"=> "View","icon"=>"")]);
//                \Yii::$app->onesignal->notifications()->create($message, $options);
				$this->_ads = $model;

				return 1;
			} else {
				return 0; // Không save được
			}
		}

		return 0;
	}
}