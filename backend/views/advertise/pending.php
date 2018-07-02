<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\AdvertiseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model common\models\Advertise */

$this->title = 'Danh sách Quảng cáo chờ duyệt';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="advertise-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'title',
            [
                'attribute' => 'thumbnail',
                'format' => 'html',
                'value' => function ($model) {
                    return $model->thumbnail_path ? Html::img(
                        \Yii::$app->glide->createSignedUrl([
                            'glide/index',
                            'path' => $model->thumbnail_path,
                            'w' => 200
                        ], true),
                        ['class' => 'img-rounded pull-left']
                    ) : null;
                },
            ],
            [
                'attribute' => 'cat_id',
                'format' => 'html',
                'value' => function ($model) {
                    $cats = \common\models\CategoryAds::findAll(['ads_id' => $model->id]);
                    $cates = '';
                    foreach ($cats as $cat) {
                        if($cat->category) {
	                        $cates .= $cat->category->name . ',<br/>';
                        }
                    }
                    return $cates;
                },
            ],
            [
                'attribute' => 'created_by',
                'value' => function ($model) {
                    return $model->created_by ? $model->author->username : '';
                },
            ],
            'share',
            'budget',
            [
                'attribute' => '',
                'format' => 'raw',
                'value' => function ($model) {
                    $check = $model->status == 1 ? '' : Html::button('Duyệt', ['value' => \yii\helpers\Url::to(['advertise/check', 'id' => $model->id]), 'title' => 'Duyệt quảng cáo', 'class' => 'showModalButton btn btn-primary']);

                    return Html::a("Xem", ['view', 'id' => $model->id], ['class' => 'btn btn-success']) . ' ' . $check;
                },
                'options' => [
                    'style' => 'width:150px;',
                ]
            ],
        ],
    ]); ?>
</div>
