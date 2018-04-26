<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\AdsCategory;
use common\models\AdsShare;
use yii\helpers\ArrayHelper;
use common\components\helper\CUtils;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\AdvertiseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model common\models\Advertise */

$this->title = 'Danh sách Quảng cáo';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="advertise-index">

    <!--    --><?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php echo Html::a('Tạo Quảng cáo', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
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
                'value' => function ($model) {
                    return $model->cat_id ? $model->category->name : '';
                },
                'filter' => ArrayHelper::map(AdsCategory::find()->all(), 'id', 'name'),
            ],
            [
                'attribute' => 'created_by',
                'value' => function ($model) {
                    return $model->created_by ? $model->author->username : '';
                },
            ],
            'share',
            [
                'attribute' => 'total_share',
                'format' => 'html',
                'value' => function ($model) {
                    return Html::a($model->id ? AdsShare::find()->where(['ads_id' => $model->id])->count()  : 0, ['/advertise/share?ads_id=' . $model->id], ['target' => '_blank']);
                },
            ],

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{view}  {update}',
            ],
        ],
    ]); ?>
</div>
