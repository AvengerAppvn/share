<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use trntv\filekit\widget\Upload;
use common\components\helper\CUtils;
use yii\helpers\ArrayHelper;

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

    <div class="col-md-3">
        <?php echo $form->field($model, 'status')->dropdownList(
            ArrayHelper::map(CUtils::status(), 'id', 'name'),
            ['prompt' => 'Chọn Trạng thái']
        ) ?>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <?php
            echo Html::submitButton(
                $model->isNewRecord ? Yii::t('backend', 'Tạo') : Yii::t('backend', 'Cập nhật'),
                ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>

