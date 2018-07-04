<?php

use common\models\Advertise;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\AdvertiseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model common\models\Advertise */

$this->title                   = 'Danh sách Quảng cáo đã hủy';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="advertise-index">

	<?= GridView::widget( [
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'columns'      => [
			'id',
			'title',
			[
				'attribute' => 'thumbnail',
				'format'    => 'html',
				'value'     => function ( $model ) {
					return $model->thumbnail_path ? Html::img(
						\Yii::$app->glide->createSignedUrl( [
							'glide/index',
							'path' => $model->thumbnail_path,
							'w'    => 200
						], true ),
						[ 'class' => 'img-rounded pull-left' ]
					) : null;
				},
			],
			[
				'attribute' => 'cat_id',
				'format'    => 'html',
				'value'     => function ( $model ) {
					$cats  = \common\models\CategoryAds::findAll( [ 'ads_id' => $model->id ] );
					$cates = '';
					foreach ( $cats as $cat ) {
						$cates .= $cat->category->name . ',<br/>';
					}

					return $cates;
				},
			],
			[
				'attribute' => 'created_by',
				'value'     => function ( $model ) {
					return $model->created_by ? $model->author->username : '';
				},
			],
			'share',
			'budget',
			'updated_at:datetime',
			[
				'attribute' => '',
				'format'    => 'raw',
				'value'     => function ( $model ) {
					$check = Advertise::STATUS_PAUSE == $model->status ? '' : Html::button( 'Duyệt', [
						'value' => Url::to( [
							'advertise/check',
							'id' => $model->id
						] ),
						'title' => 'Duyệt quảng cáo',
						'class' => 'showModalButton btn btn-primary'
					] );

					return Html::a( "Xem", [
							'view',
							'id' => $model->id
						], [ 'class' => 'btn btn-success' ] ) . ' ' . $check;
				},
				'options'   => [
					'style' => 'width:150px;',
				]
			],
		],
	] ); ?>
</div>
