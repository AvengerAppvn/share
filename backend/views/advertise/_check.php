<?php

use common\models\AdsCategory;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Advertise */
/* @var $form yii\widgets\ActiveForm */

$cats = ArrayHelper::map(AdsCategory::find()->where(['status' => 1])->all(), 'id', 'name');
?>

<div class="advertise-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12">
                    <h3>Tiêu đề: <?= $model->title ?></h3>

                    <h3>Danh mục:</h3>
                    <ul>
                        <?php
                        $cats = \common\models\CategoryAds::findAll(['ads_id' => $model->id]);
                        foreach ($cats as $cat) {
                            echo '<li>' . $cat->category->name . '</li>';
                        }
                        ?>
                    </ul>


                    <h4>Yêu cầu: </h4>
                    <p><?php echo $model->require ?></p>


                    <h4>Thông điệp:</h4>
                    <p><?php echo $model->message ?></p>
                </div>
                <div class="col-md-12">

                    <h4>Tiêu chí Share:</h4>


                    <?php echo $model->province_id ?>


                    <?php echo $model->age_id ?>

                    <?php echo $model->speciality_id ?>


                    <h4>
                        Ngân sách chi cho quảng cáo: <b><?= $model->budget ?></b>
                    </h4>
                    <h4>
                        Số lượt share khả dụng: <b><?= $model->share ?></b>
                    </h4>
                    <div class="col-md-3">
                        <?php echo $form->field($model, 'status')->dropdownList(
                            ArrayHelper::map(\common\components\helper\CUtils::statusCheck(), 'id', 'name'),
                            ['prompt' => null,'style'=>'width:180px']
                        ) ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <?= Html::submitButton('Xác nhận', ['class' => 'btn btn-lg btn-success']) ?>
                <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">Bỏ qua</button>
            </div>
        </div>
        <div class="col-md-6">
            <h2>Ảnh của quảng cáo</h2>
            <div class="row">
                <?php
                foreach ($images as $image) {
                    echo '<div class="col-md-6">'.Html::img($image, ['width' => 120]).'</div>';
                }
                ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
