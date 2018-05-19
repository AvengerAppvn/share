<?php

use common\components\helper\CUtils;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel common\models\HistorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Lịch sử';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="history-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'attribute' => 'user_id',
                'value' => function ($model) {
                    return $model->user_id ? $model->user->username : "";
                },
            ],
            'amount',
            'description',
            [
                'attribute' => 'type',
                'value' => function ($model) {
                    return $model->type == 2 ? "Nạp tiền" : "Rút tiền";
                },
                'filter' => ArrayHelper::map(CUtils::typeRequest(), 'id', 'name'),
            ],
             'status',
             'created_at:datetime',
            // 'updated_at',
             [
                'attribute' => 'created_by',
                'value' => function ($model) {
                    return $model->created_by ? $model->author->username : "";
                },
            ],
            // 'updated_by',

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
