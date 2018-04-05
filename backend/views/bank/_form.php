<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\components\helper\CUtils;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Bank */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bank-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="col-md-12">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
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
