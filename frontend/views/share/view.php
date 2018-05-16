<?php
/* @var $this yii\web\View */
/* @var $model common\models\Advertise */
$this->title = $model->title;
?>
<div class="content">
    <div class="row share-item">
        <div class="col-md-12">
            <h1><?php echo $model->title ?></h1>
            <p>Đăng lúc: <?php echo date('H:i A - d/m/y', $model->created_at); ?></p>
        </div>
        <div class="col-md-12">
            <h5>
                <?php echo $model->message ?>
            </h5>

                <?php if ($model->advertiseImages): ?>
                    <div class="row">
                        <?php foreach ($model->advertiseImages as $image): ?>
                        <div class="col-md-6">
                                <?php echo \yii\helpers\Html::img($image->image,['class' => 'img-responsive']) ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
        </div>
    </div>