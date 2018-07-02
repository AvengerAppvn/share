<?php

namespace frontend\models;

use common\models\AdsAdvertiseImage;
use common\models\AdsCategory;
use common\models\CategoryAds;
use common\models\Transaction;
use trntv\filekit\Storage;
use Yii;
use yii\base\Model;
use yii\di\Instance;

/**
 * Ads form
 */
class AdsForm extends Model {
	public $title;
	public $images;
	public $location;
	public $require;
	public $message;
	public $age;
	public $category;
	public $budget;
	public $user_id;
	public $age_min;
	public $age_max;
	public $ads_type;
	public $time_type;
	public $price_suggest;

	public $_ads;

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[ 'title', 'trim' ],
			//['title', 'required', 'message' => Yii::t('frontend', 'Missing title')],
			[ 'budget', 'required', 'message' => Yii::t( 'frontend', 'Thiếu ngân sách dự chi' ) ],
			[ 'require', 'required', 'message' => Yii::t( 'frontend', 'Thiếu Yêu cầu' ) ],
			[ [ 'title', 'require', 'message' ], 'string' ],
			[ 'age_max', 'integer', 'max' => 80 ],
			[ 'age_min', 'integer', 'min' => 18 ],
			[ [ 'ads_type', 'time_type', 'price_suggest' ], 'integer' ],
			[ [ 'age_max' ], 'compare', 'compareAttribute' => 'age_min', 'operator' => '>=', 'skipOnEmpty' => true ],
			[ [ 'images', 'location', 'age', 'category' ], 'safe' ]
		];
	}

	protected function getPriceUnit( $price_base ) {
		$percent = \Yii::$app->keyStorage->get( 'config.service', 20 );

		$option     = (int) \Yii::$app->keyStorage->get( 'config.option', 10 );
		$price_unit = $price_base;
		if ( $this->location && $this->location > 0 ) {
			$price_unit += $price_base * $option / 100;
		}
		if ( $this->age && $this->age > 0 ) {
			$price_unit += $price_base * $option / 100;
		}

		if ( $this->category && $this->category > 0 ) {
			$price_unit += $price_base * $option / 100;
		}
		$price_unit += $price_base * $percent / 100;

		return $price_unit;
	}


	/**
	 * @param $user_id ,$budget,$title
	 */
	protected function transaction( $user_id, $budget, $title ) {
		$transaction              = new Transaction();
		$transaction->description = $title;
		$transaction->user_id     = $user_id;
		$transaction->amount      = $budget;
		$transaction->type        = Transaction::TYPE_WITHDRAW; // Chi
		$transaction->save();
	}

	/**
	 * @param $primaryKey
	 * @param $model
	 *
	 * @throws \yii\base\InvalidConfigException
	 */
	protected function createImages( $ads_id, $model ) {
		// requires php5
		$date      = date( "Ym" );
		$path      = 'shares/' . $date . '/' . $ads_id . '/';
		$directory = \Yii::getAlias( '@storage' ) . '/web/source/' . $path;
		if ( ! is_dir( $directory ) ) {
			mkdir( $directory, 755, true );
		}
		$fileStorage = Instance::ensure( 'fileStorage', Storage::className() );
		$index = 0;
		foreach ( $this->images as $image ) {
			$adsImage         = new AdsAdvertiseImage();
			$adsImage->ads_id = $ads_id;
			$img              = $image;
			$img              = str_replace( 'data:image/png;base64,', '', $img );
			$img              = str_replace( ' ', '+', $img );
			$data             = base64_decode( $img );

			$filename = uniqid() . '.png';
			$file     = $directory . $filename;
			$success  = file_put_contents( $file, $data );

			$adsImage->image_base_url = $success ? $fileStorage->baseUrl : '';
			$adsImage->image_path     = $success ? $path . $filename : '';
			$adsImage->save();

			// Lấy ảnh đầu tiên làm thumbnail
			if ($index == 0 ) {
				$model->thumbnail_base_url = $fileStorage->baseUrl;
				$model->thumbnail_path     = $path . $filename;
				$model->save( false );
			}
			$index++;
		}
	}

	/**
	 * @param $model
	 * @param $primaryKey
	 * @param $tags
	 */
	protected function addCategories( $cat_id, $ads_id,$isDelete = false ) {
		$tags = [];
		// Danh mục cat_id = 0 sẽ là tất cả danh mục
		if ( $cat_id) {
			$categories = AdsCategory::find()->where( [ 'id' => $cat_id ] )->all();

		} else {
			$categories = AdsCategory::find()->all();
		}

		if($isDelete){
			// Xóa tất cả category
			CategoryAds::deleteAll( [ 'ads_id' => $ads_id ] );
		}
		foreach ( $categories as $category ) {

			$catAds         = new CategoryAds();
			$catAds->cat_id = $category->id;
			$catAds->ads_id = $ads_id;
			$catAds->save();
			$tags[] = $category->slug;
		}

		return $tags;
	}
}