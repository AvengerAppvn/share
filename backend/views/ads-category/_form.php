<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use trntv\filekit\widget\Upload;

/* @var $this yii\web\View */
/* @var $model common\models\AdsCategory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ads-category-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="col-md-12">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-md-12">
        <?php echo $form->field($model, 'description')->widget(
            \yii\imperavi\Widget::className(),
            [
                'plugins' => ['fullscreen', 'fontcolor'],
                'options' => [
                    'minHeight' => 240,
                    'maxHeight' => 360,
                    'buttonSource' => true,
                    'convertDivs' => false,
                    'removeEmptyTags' => false,
                    'imageUpload' => Yii::$app->urlManager->createUrl(['/file-storage/upload-imperavi'])
                ]
            ]
        ) ?>
    </div>

    <div class="col-md-12">
        <?php
        echo $form->field($model, 'image')->widget(
            Upload::className(),
            [
                'url' => ['/file-storage/upload'],
                'maxFileSize' => 5000000, // 5 MiB
            ]);
        ?>
    </div>

    <div class="col-md-12">
        <?php echo $form->field($model, 'status')->checkbox(
            [
                'label' => 'Kích hoạt',
            ]
        ) ?>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <?php
            echo Html::submitButton(
                $model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'),
                ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>

