<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use common\components\helper\CUtils;
/* @var $this yii\web\View */
/* @var $searchModel common\models\search\BankSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Banks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bank-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Bank', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'id',
                'headerOptions' => ['style' => 'width:50px'],
            ],
            [
                'attribute' => 'thumbnail',
                'format' => 'html',
                'value' => function ($model) {
                    return $model->thumbnail_path ? Html::img(
                        \Yii::$app->glide->createSignedUrl([
                            'glide/index',
                            'path' => $model->thumbnail_path,
                            'w' => 120
                        ], true),
                        ['class' => 'img-rounded pull-left']
                    ) : null;
                },
            ],
            'name',
            [
                'attribute' => 'fee_bank',
                'value' => function ($model) {
                    return $model->fee_bank == 1 ? "Mất phí" : "Không mất phí";
                },
                'filter' => ArrayHelper::map(CUtils::feeBank(), 'id', 'name'),
            ],
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->status == 1 ? "Kích hoạt" : "Đóng";
                },
                'filter' => ArrayHelper::map(CUtils::status(), 'id', 'name'),
            ],
//            'created_at',
//            'updated_at',
            // 'created_by',
            // 'updated_by',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
