<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\AdsAdvertiseShare */

$this->title = 'Create Ads Advertise Share';
$this->params['breadcrumbs'][] = ['label' => 'Ads Advertise Shares', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ads-advertise-share-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
