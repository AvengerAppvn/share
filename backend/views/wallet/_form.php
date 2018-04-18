<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\components\helper\CUtils;
use yii\helpers\ArrayHelper;


/* @var $this yii\web\View */
/* @var $model common\models\Wallet */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wallet-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'user_id')->textInput() ?>
        </div>

        <div class="col-md-12">
            <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-3">
            <?php echo $form->field($model, 'status')->dropdownList(
                ArrayHelper::map(CUtils::status(), 'id', 'name'),
                ['prompt' => 'Chọn Trạng thái']
            ) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
