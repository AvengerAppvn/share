<?php
/* @var $this yii\web\View */
/* @var $model common\models\Advertise */
$this->title = $model->title;
?>
<div class="content">
    <article class="article-item">
        <h1><?php echo $model->title ?></h1>

        <?php if ($model->thumbnail_path): ?>
            <?php echo \yii\helpers\Html::img(
                Yii::$app->glide->createSignedUrl([
                    'glide/index',
                    'path' => $model->thumbnail_path,
                    'w' => 200
                ], true),
                ['class' => 'article-thumb img-rounded pull-left']
            ) ?>
        <?php endif; ?>

        <?php echo $model->message ?>

        <?php if (!empty($model->advertiseImages)): ?>
            <ul id="ads-images">
                <?php foreach ($model->advertiseImages as $image): ?>
                    <li>
                        <?php echo \yii\helpers\Html::img(
                            Yii::$app->glide->createSignedUrl([
                                'glide/index',
                                'path' => $image->image_path,
                                'w' => 200
                            ], true),
                            ['class' => 'article-thumb img-rounded pull-left']
                        ) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

    </article>
</div>