<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use common\components\helper\CUtils;

/* @var $this yii\web\View */
/* @var $model common\models\Request */

$this->title = $model->user->username.': '.$model->description;
$this->params['breadcrumbs'][] = ['label' => 'Danh sách giao dịch', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="request-view">
    <div class="row">
    <div class="col-md-6">
    <p>
        <?= $model->status != 1 ? Html::a("Duyệt", ['update', 'id' => $model->id], ['target' => '_blank', 'class' => 'btn btn-primary']) : ""; ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            [
                'attribute' => 'user_id',
                'value' => function ($model) {
                    return $model->user_id ? $model->user->username : "";
                },
            ],
            [
                'attribute' => 'amount',
                'value' => function ($model) {
                    return number_format($model->amount);
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
            'created_at:datetime',
            [
                'attribute' => 'updated_by',
                'value' => function ($model) {
                    return $model->updated_by ? $model->updater->username : "";
                },
            ],
            'updated_at:datetime',
        ],
    ]) ?>
    </div>
        <div class="col-md-6">
            <h3>Ảnh chụp giao dịch:</h3>
            <?php echo Html::img($model->image, ['class' => 'img-responsive']); ?>
        </div>
    </div>
</div>
