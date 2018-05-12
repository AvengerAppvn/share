<?php

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

            'id',
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
                    if ($model->type == 1) {
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
                    return $model->type == 1 ? "Nạp tiền" : "Rút tiền";
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
                'format' => 'html',
                'value' => function ($model) {
                    return $model->status == 1 ?
                        Html::a("Xem", ['view', 'id' => $model->id], ['target' => '_blank', 'class' => 'btn btn-success']) :
                        (Html::a("Duyệt", ['update', 'id' => $model->id], ['target' => '_blank', 'class' => 'btn btn-primary'])
                            . ' ' . Html::a("Xem", ['view', 'id' => $model->id], ['target' => '_blank', 'class' => 'btn btn-success']));
                },
                'options' => [
                    'style' => 'width:150px;',
                ]
            ],

        ],
    ]); ?>
</div>
