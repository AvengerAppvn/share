<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use common\components\helper\CUtils;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\CriteriaProvinceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Criteria Provinces';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="criteria-province-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Criteria Province', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'slug',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->status == 1 ? "Mở" : "Đóng";
                },
                'filter' => ArrayHelper::map(CUtils::status(), 'id', 'name'),
            ],
            // 'created_at',
            // 'updated_at',
            // 'created_by',
            // 'updated_by',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
