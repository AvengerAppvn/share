<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use common\components\helper\CUtils;
use common\models\AdsCategory;
use common\models\AdsShare;

/* @var $this yii\web\View */
/* @var $model common\models\Advertise */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Advertises', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="advertise-view">
    <div class="row">
        <div class="col-md-4">
            <p>
                <?php echo Html::a('Cập nhật', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= $model->share != 1 ? Html::a('Xóa', ['delete', 'id' => $model->id], [
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
                    'require:html',
                    'message:html',
                    'budget',
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
                    'share',
                    [
                        'attribute' => 'status',
                        'value' => function ($model) {
                            return $model->status == 1 ? "Hoạt động" : "Ngưng";
                        },
                        'filter' => ArrayHelper::map(CUtils::status(), 'id', 'name'),
                    ],
                    'created_at:datetime',
                    'updated_at:datetime',
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
        <div class="col-md-8">
            <h2>Ảnh của quảng cáo</h2>
            <?php
                foreach($images as $image){
                    echo Html::img($image,['width'=>240]);
                }
            ?>
        </div>
    </div>


</div>
