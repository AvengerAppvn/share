<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\AdsCategory;
use common\models\CriteriaSpeciality;
use common\models\CriteriaProvince;
use common\models\CriteriaAge;
use common\components\helper\CUtils;

/* @var $this yii\web\View */
/* @var $model common\models\Advertise */
/* @var $form yii\widgets\ActiveForm */

//var_dump($model);die;
?>

<div class="advertise-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="col-md-12">

        <div class="row">
            <div class="col-md-8">
                <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
            </div>

            <div class="col-md-4">
                <?php echo $form->field($model, 'cat_id')->dropdownList(
                    ArrayHelper::map(AdsCategory::find()->where(['status' => 1])->all(), 'id', 'name'),
                    ['prompt' => 'Chọn Danh mục']
                );
                ?>
            </div>
        </div>

        <div class="col-md-12">
            <?php echo $form->field($model, 'content')->widget(
                \yii\imperavi\Widget::className(),
                [
                    'plugins' => ['fullscreen', 'fontcolor', 'video'],
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

        <div class="row">

            <div class="col-md-6">
                <?php echo $form->field($model, 'description')->widget(
                    \yii\imperavi\Widget::className(),
                    [
                        'plugins' => ['fullscreen', 'fontcolor', 'video'],
                        'options' => [
                            'minHeight' => 180,
                            'maxHeight' => 240,
                            'buttonSource' => true,
                            'convertDivs' => false,
                            'removeEmptyTags' => false,
                            'imageUpload' => Yii::$app->urlManager->createUrl(['/file-storage/upload-imperavi'])
                        ]
                    ]
                ) ?>
            </div>

            <div class="col-md-6">
                <?php echo $form->field($model, 'message')->widget(
                    \yii\imperavi\Widget::className(),
                    [
                        'plugins' => ['fullscreen', 'fontcolor', 'video'],
                        'options' => [
                            'minHeight' => 180,
                            'maxHeight' => 240,
                            'buttonSource' => true,
                            'convertDivs' => false,
                            'removeEmptyTags' => false,
                            'imageUpload' => Yii::$app->urlManager->createUrl(['/file-storage/upload-imperavi'])
                        ]
                    ]
                ) ?>
            </div>

        </div>

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
                    ArrayHelper::map(CriteriaSpeciality::find()->where(['status' => 1])->all(), 'id', 'name'),
                    ['prompt' => 'Chọn Chuyên ngành']
                );
                ?>
            </div>

        </div>

        <div class="col-md-12">
            <?php echo $form->field($model, 'thumbnail')->widget(
                \trntv\filekit\widget\Upload::className(),
                [
                    'url' => ['/file-storage/upload'],
                    'maxFileSize' => 5000000, // 5 MiB
                ]);
            ?>
        </div>

        <div class="row">
            <div class="col-md-3">
                <?php echo $form->field($model, 'share')->dropdownList(
                    ArrayHelper::map(CUtils::status(), 'id', 'name'),
                    ['prompt' => 'Chọn Share quảng cáo']
                );
                ?>
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
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>

    </div>

    <?php ActiveForm::end(); ?>

</div>
