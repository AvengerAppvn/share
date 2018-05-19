<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Request */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="request-form">


    <div class="row">
        <div class="col-md-6">
            <?php $form = ActiveForm::begin(); ?>
            <p>Tài khoản <?= $model->user->username ?></p>
            <p>Nội dung: <?= $model->description ?></p>
            <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>

            <div class="form-group">
                <?= Html::submitButton( 'Xác nhận', ['class' => 'btn btn-primary']) ?>
                <button type="button" class="btn btn-default" data-dismiss="modal">Bỏ qua</button>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
        <div class="col-md-6">
            <h3>Ảnh chụp giao dịch:</h3>
            <?php echo Html::img($model->image, ['class' => 'img-responsive']); ?>
        </div>
    </div>
</div>
