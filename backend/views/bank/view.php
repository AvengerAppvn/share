<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\components\helper\CUtils;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Bank */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Bank1s', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bank-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->status == 1 ? "Kích hoạt" : "Đóng";
                },
                'filter' => ArrayHelper::map(CUtils::status(), 'id', 'name'),
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
