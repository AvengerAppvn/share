<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Xác thực';
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Yêu cầu xác minh'), 'url' => ['request']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-view">

        <div class="col-md-12">
            <h2> Xác thực tài khoản</h2>
            <?php
            if ($model->user->status_confirmed == 1) { ?>

                <div class="row">
                    <div class="col-md-12">
                        <h3>Số CMT: <strong class="text-primary"><?php echo $model->cmt; ?></strong> <strong class="text-success">Đã xác thực</strong></h3>
                    </div>
                </div>
            <?php } else { ?>

                <div class="col-md-6 identity-card">
                    <h4>Số chứng minh thư:</h4>
                    <?php $form = ActiveForm::begin(); ?>

                    <div class="form-group inline">
                        <div class="row">
                            <div class="col-md-3">
                        <?= $form->field($model, 'cmt')->textInput()->label(false) ?>
                            </div>
                            <div class="col-md-3">
                                <?php echo Html::submitButton(Yii::t('backend', 'Xác thực tài khoản'), ['class' => 'btn btn-primary', 'name' => 'confirm-button']) ?>
                            </div>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>

            <?php } ?>
        </div>
        <div class="col-md-6 image">
            <h3>CMT mặt 1:</h3>
            <?php echo Html::img($model->image_id_1, ['class' => 'img-comfirm', 'style' => 'padding: 10px; width:500px; height: 350px; ']); ?>
        </div>
        <div class="col-md-6 image">
            <h3>CMT mặt 2:</h3>
            <?php echo Html::img($model->image_id_2, ['class' => 'img-comfirm', 'style' => 'padding: 10px; width:500px; height: 350px']); ?>
        </div>
        <div class="col-md-12 image">
            <h3> Danh sách bạn bè:</h3>
            <?php echo Html::img($model->image_friend_list, ['class' => 'img-comfirm', 'style' => 'padding: 10px; width:500px; height: 350px']); ?>
        </div>

        <input type="hidden" id="user_id" value="<?php echo $model->user_id; ?>"/>
    </div>
