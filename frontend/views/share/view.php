<?php
/* @var $this yii\web\View */
/* @var $model common\models\Advertise */
$this->title = $model->title;
?>
<div class="content">
    <div class="row share-item">
        <div class="col-md-12">
            <h1><?php echo $model->title ?></h1>
            <p><?= $model->created_at?></p>
            <p>Đăng lúc: <?php echo date('H:i A - d/m/y', strtotime($model->created_at*1000)); ?></p>
        </div>
        <div class="col-md-12">
            <h3>
                <?php echo $model->message ?>
            </h3>

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