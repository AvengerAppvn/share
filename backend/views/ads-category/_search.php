<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\search\AdsCategorySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ads-category-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <!--    --><?php //echo $form->field($model, 'id') ?>
    <div class="col-md-12 col-md-offset-8 ">
        <div class="right">
            <div class="col-md-3">
                <?= $form->field($model, 'name')->label(false)->textInput(['placeholder' => "Nhập tên danh mục"]) ?>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <?= Html::submitButton('Tìm kiếm', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
    </div>

    <!--    --><?php //echo $form->field($model, 'slug') ?>
    <!---->
    <!--    --><?php //echo $form->field($model, 'description') ?>
    <!---->
    <!--    --><?php //echo $form->field($model, 'image_base_url') ?>

    <?php // echo $form->field($model, 'image_path') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php ActiveForm::end(); ?>

</div>
