<?php

use trntv\yii\datetime\DateTimeWidget;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\search\PaymentSearch */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="user-search">

    <?php $form = ActiveForm::begin([
        //'action' => ['view'],
        'method' => 'get',
    ]); ?>
    <!--    --><?php //echo Html::hiddenInput('PaymentSearch[user_id]',$user_id) ?>
    <?= Html::hiddenInput('id', $model->id) ?>
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-3 col-sm-4 col-xs-6 form-inline">
                <?php echo $form->field($model, 'min_date')->widget(
                    DateTimeWidget::className(),
                    [
                        'phpDatetimeFormat' => 'yyyy-MM-dd',
                    ]
                )->label('Từ ngày') ?>
            </div>

            <div class="col-md-3 col-sm-4 col-xs-6 form-inline">

                <?php echo $form->field($model, 'max_date')->widget(
                    DateTimeWidget::className(),
                    [
                        'phpDatetimeFormat' => 'yyyy-MM-dd'
                    ]
                )->label('Đến ngày') ?>

            </div>
            <div class="col-md-4 text-left">
                <?php echo Html::submitButton(Yii::t('backend', 'Xem'), ['class' => 'btn btn-primary']) ?>
            </div>

        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>