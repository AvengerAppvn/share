<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\SystemLogEndpointSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'System Logs Endpoint');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="system-log-index">

    <p>
        <?php echo Html::a(Yii::t('app', 'Clear'), false, ['class' => 'btn btn-danger', 'data-method'=>'delete']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => [
            'class' => 'grid-view table-responsive'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'action',
            'method',
            //'param',
            [
                'attribute' => 'created_at',
                'format' => 'datetime',
                'value' => function ($model) {
                    return (int) $model->created_at;
                }
            ],
            [
                'attribute' => 'updated_at',
                'format' => 'datetime',
                'value' => function ($model) {
                    return (int) $model->updated_at;
                }
            ],
            'count_time',
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{view}{delete}'
            ]
        ]
    ]); ?>

</div>
