<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use trntv\yii\datetime\DateTimeWidget;

/* @var $this yii\web\View */
/* @var $model common\models\search\PaymentSearch */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['view'],
        'method' => 'get',
    ]); ?>
    <!--    --><?php //echo Html::hiddenInput('PaymentSearch[user_id]',$user_id) ?>
    <?= Html::hiddenInput('id',$user_id) ?>
    <div class="col-md-12 transaction ">
        <ul class="row logtime-transaction">
            <li class="row">
                <div class="col-md-3 col-sm-3 col-xs-3">
                    <span class="history-range pull-right">Từ ngày</span>
                </div>

                <div class="col-md-6 col-sm-6 col-xs-6">
                    <?php echo $form->field($model, 'min_date')->widget(
                        DateTimeWidget::className(),
                        [
                            'phpDatetimeFormat' => 'yyyy-MM-dd',
                        ]
                    )->label(false) ?>
                </div>
            </li>

            <li class="row">
                <div class="col-md-3 col-sm-3 col-xs-3">
                    <span class="history-range pull-right">Đến ngày</span>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-6">

                    <?php echo $form->field($model, 'max_date')->widget(
                        DateTimeWidget::className(),
                        [
                            'phpDatetimeFormat' => 'yyyy-MM-dd'
                        ]
                    )->label(false) ?>

                </div>
                <div class="col-md-2">
                    <?php echo Html::submitButton(Yii::t('backend', 'Xem'), ['class' => 'btn btn-primary']) ?>
                </div>
            </li>
        </ul>
    </div>
    <?php ActiveForm::end(); ?>

</div>