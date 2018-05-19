<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use common\components\helper\CUtils;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\RequestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Yêu cầu giao dịch';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="request-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
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
            [
                'attribute' => 'amount',
                'format' => 'html',
                'value' => function ($model) {
                    if ($model->type == 2) {
                        return '<span style="color: #00CC00"> + ' . $model->amount . '</span>';
                    } else {
                        return '<span style="color: red"> - ' . $model->amount . '</span>';
                    }
                },
            ],
            'description',
            [
                'attribute' => 'type',
                'value' => function ($model) {
                    return $model->type == 2 ? "Nạp tiền" : "Rút tiền";
                },
                'filter' => ArrayHelper::map(CUtils::typeRequest(), 'id', 'name'),
            ],
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->status == 1 ? "Đã duyệt" : "Đang chờ";
                },
                'filter' => ArrayHelper::map(CUtils::statusRequest(), 'id', 'name'),
            ],
            [
                'attribute' => '',
                'format' => 'raw',
                'value' => function ($model) {
                    $check =  $model->status == 1 ? '' : Html::button('Duyệt', ['value' => Url::to(['request/check', 'id'=>$model->id]), 'title' => 'Xét duyệt yêu cầu', 'class' => 'showModalButton btn btn-primary']);

                    return Html::a("Xem", ['view', 'id' => $model->id], ['class' => 'btn btn-success']).' '. $check;
                },
                'options' => [
                    'style' => 'width:150px;',
                ]
            ],

        ],
    ]); ?>
</div>
