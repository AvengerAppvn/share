<?php

use common\components\helper\CUtils;
use common\models\AdsCategory;
use common\models\CriteriaAge;
use common\models\CriteriaProvince;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Advertise */
/* @var $form yii\widgets\ActiveForm */

$cats =  ArrayHelper::map(AdsCategory::find()->where(['status' => 1])->all(), 'id', 'name');
?>

<div class="advertise-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="col-md-12">

        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php
                echo $form->field($model, 'cat_id')->checkboxList($cats, [
//                    'separator' => '<br>',
                    'itemOptions' => [
                        'class' => 'checkbox-category'
                    ]
                ]);
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php echo $form->field($model, 'require')->widget(
                    \yii\imperavi\Widget::className(),
                    [
                        'options' => [
                            'minHeight' => 120,
                            'maxHeight' => 240,
                            'buttonHtml' => false,
                            'convertDivs' => false,
                            'removeEmptyTags' => true,
                        ]
                    ]
                ) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <?php echo $form->field($model, 'message')->widget(
                    \yii\imperavi\Widget::className(),
                    [
                        'options' => [
                            'buttons' => ['formatting', 'bold', 'italic', 'deleted', 'unorderedlist', 'orderedlist',
				  'outdent', 'indent', 'link', 'alignment'],
                            'minHeight' => 120,
                            'maxHeight' => 240,
                            'convertDivs' => true,
                            'removeEmptyTags' => true,
                        ]
                    ]
                ) ?>
            </div>
        </div>

        <!--        </div>-->

        <div class="row">

            <h3>Tiêu chí Share:</h3>

            <div class="col-md-4">
                <?php echo $form->field($model, 'province_id')->dropdownList(
                    ArrayHelper::map(CriteriaProvince::find()->where(['status' => 1])->all(), 'id', 'name'),
                    ['prompt' => 'Chọn Thành Phố']
                );
                ?>
            </div>

            <div class="col-md-4">
                <?php echo $form->field($model, 'age_id')->dropdownList(
                    ArrayHelper::map(CriteriaAge::find()->where(['status' => 1])->all(), 'id', 'name'),
                    ['prompt' => 'Chọn Độ tuổi']
                );
                ?>
            </div>

            <div class="col-md-4">
                <?php echo $form->field($model, 'speciality_id')->dropdownList(
                    ArrayHelper::map(AdsCategory::find()->where(['status' => 1])->all(), 'id', 'name'),
                    ['prompt' => 'Chọn Chuyên ngành']
                );
                ?>
            </div>

        </div>
        <div class="row">
            <div class="col-md-12">
                <?php echo $form->field($model, 'thumbnail')->widget(
                    \trntv\filekit\widget\Upload::className(),
                    [
                        'url' => ['/file-storage/upload'],
                        'maxFileSize' => 5000000, // 5 MiB
                    ]);
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <?= $form->field($model, 'share')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-3">
                <?php echo $form->field($model, 'status')->dropdownList(
                    ArrayHelper::map(CUtils::status(), 'id', 'name'),
                    ['prompt' => 'Chọn Trạng thái']
                ) ?>
            </div>
        </div>

        <div class="row">
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Tạo' : 'Cập nhật', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>

    </div>

    <?php ActiveForm::end(); ?>

</div>
