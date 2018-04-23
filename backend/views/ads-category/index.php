<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use common\components\helper\CUtils;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\AdsCategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Danh sách Danh mục';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ads-category-index">
    <div class="row">
        <div class="col-md-3">
            <?= Html::a('Tạo danh mục', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div class="col-md-9">
            <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            [
                'attribute' => 'image_base_url',
                'format' => 'html',
                'value' => function ($model) {
                    return $model->image_base_url ? Html::img($model->thumbnail) : null;
                },
            ],

            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->status == 1 ? "Kích hoạt" : "Đóng";
                },
                'filter' => ArrayHelper::map(CUtils::status(), 'id', 'name'),
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
