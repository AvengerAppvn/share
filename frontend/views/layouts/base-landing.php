<?php

use yii\bootstrap\NavBar;

/* @var $this \yii\web\View */
/* @var $content string */

$this->beginContent('@frontend/views/layouts/_clear.php')
?>
    <div class="wrap landing">
        <?php
        NavBar::begin([
            'brandLabel' => Yii::$app->name,
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'red navbar-inverse navbar-fixed-top',
            ],
        ]); ?>
        <?php echo \common\widgets\DbMenu::widget(['key' => 'landingpage', 'options' => ['class' => 'nav navbar-nav'],]); ?>
        <?php NavBar::end(); ?>

        <?php echo $content ?>

    </div>

<?php $this->endContent() ?>