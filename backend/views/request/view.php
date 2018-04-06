<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use common\components\helper\CUtils;

/* @var $this yii\web\View */
/* @var $model common\models\Request */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="request-view">

    <p>
        <?= $model->status != 1 ? Html::a("Duyệt", ['update', 'id' => $model->id], ['target' => '_blank', 'class' => 'btn btn-primary']) : ""; ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'user_id',
            'amount',
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
            'created_at',
            'updated_at',
            [
                'attribute' => 'created_by',
                'value' => function ($model) {
                    return $model->created_by ? $model->author->username : '';
                },
            ],
            [
                'attribute' => 'updated_by',
                'value' => function ($model) {
                    return $model->updated_by ? $model->updater->username : '';
                },
            ],
        ],
    ]) ?>

</div>
