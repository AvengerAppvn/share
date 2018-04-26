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
            'id',
            'name',
            [
                'attribute' => 'image',
                'format' => 'html',
                'value' => function ($model) {
                    return $model->image_path ? Html::img(\Yii::$app->glide->createSignedUrl([
                        'glide/index',
                        'path' => $model->image_path,
                        'w' => 200
                    ], true),
                        ['class' => 'img-rounded pull-left']
                    ) : null;
                },
            ],

            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->status == 1 ? "Hoạt động" : "Ngưng";
                },
                'filter' => ArrayHelper::map(CUtils::status(), 'id', 'name'),
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
