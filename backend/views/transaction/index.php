<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use common\components\helper\CUtils;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\TransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Transactions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaction-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

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
//            [
//                'attribute' => 'status',
//                'value' => function ($model) {
//                    return $model->status == 1 ?  "Kích Hoạt" : "Đóng";
//                },
//                'filter' => ArrayHelper::map(CUtils::statusRequest(), 'id', 'name'),
//            ],

//            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
