<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\components\helper\CUtils;
use yii\helpers\ArrayHelper;
use common\models\Bank;
use common\models\CriteriaProvince;

/* @var $this yii\web\View */
/* @var $model common\models\UserBank */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-bank-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="col-md-12">
        <?= $form->field($model, 'account_name')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-md-12">
        <?= $form->field($model, 'account_number')->textInput() ?>
    </div>

    <div class="col-md-12">

        <div class="col-md-3">
            <?php echo $form->field($model, 'bank_id')->dropdownList(
                ArrayHelper::map(Bank::find()->where(['status' => 1])->all(), 'id', 'name'),
                ['prompt' => 'Chọn Ngân hàng']
            );
            ?>
        </div>

        <div class="col-md-3">
            <?php echo $form->field($model, 'province_id')->dropdownList(
                ArrayHelper::map(CriteriaProvince::find()->where(['status' => 1])->all(), 'id', 'name'),
                ['prompt' => 'Chọn Thành phố']
            );
            ?>
        </div>

    </div>

    <div class="col-md-12">
        <?= $form->field($model, 'branch_name')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-md-3">
        <?php echo $form->field($model, 'status')->dropdownList(
            ArrayHelper::map(CUtils::status(), 'id', 'name'),
            ['prompt' => 'Chọn Trạng thái']
        ) ?>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
