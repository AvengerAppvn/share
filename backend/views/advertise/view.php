<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use common\components\helper\CUtils;
use common\models\AdsCategory;
use common\models\AdsShare;

/* @var $this yii\web\View */
/* @var $model common\models\Advertise */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Advertises', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="advertise-view">

    <p>
        <?php echo Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= $model->share != 1 ? Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) : '' ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'cat_id',
                'value' => function ($model) {
                    return $model->cat_id ? $model->category->name : '';
                },
                'filter' => ArrayHelper::map(AdsCategory::find()->all(), 'id', 'name'),
            ],
            'title',
//            'slug',
            'content:ntext',
            'description',
            'message',
            [
                'attribute' => 'province_id',
                'value' => function ($model) {
                    return $model->province_id ? $model->province->name : "";
                },
            ],
            [
                'attribute' => 'age_id',
                'value' => function ($model) {
                    return $model->age_id ? $model->age->name : "";
                },
            ],
            [
                'attribute' => 'speciality_id',
                'value' => function ($model) {
                    return $model->speciality_id ? $model->speciality->name : "";
                },
            ],
            [
                'attribute' => 'share',
                'value' => function ($model) {
                    return $model->share == 1 ? "Đã Share" : "Chưa Share";
                },
                'filter' => ArrayHelper::map(CUtils::shareStatus(), 'id', 'name'),
            ],
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->status == 1 ? "Kích hoạt" : "Đóng";
                },
                'filter' => ArrayHelper::map(CUtils::status(), 'id', 'name'),
            ],
            'thumbnail_base_url',
            'thumbnail_path',
            'created_at',
            'updated_at',
            [
                'attribute' => 'created_at',
                'value' => function ($model) {
                    return date("d-m-Y H:i:s", strtotime($model->created_at));;
                },
            ],
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
