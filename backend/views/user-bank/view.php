<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\CriteriaProvince;
use common\models\Bank;
use yii\helpers\ArrayHelper;
use common\components\helper\CUtils;

/* @var $this yii\web\View */
/* @var $model common\models\UserBank */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'User Banks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-bank-view">

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
            'account_name',
            'account_number',
            [
                'attribute' => 'bank_id',
                'value' => function ($model) {
                    return $model->bank_id ? $model->bank->name : '';
                },
                'filter' => ArrayHelper::map(Bank::find()->all(), 'id', 'name'),
            ],
            [
                'attribute' => 'province_id',
                'value' => function ($model) {
                    return $model->province_id ? $model->province->name : '';
                },
                'filter' => ArrayHelper::map(CriteriaProvince::find()->all(), 'id', 'name'),
            ],
            'branch_name',
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
