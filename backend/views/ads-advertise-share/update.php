<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\AdsAdvertiseShare */

$this->title = 'Update Ads Advertise Share: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ads Advertise Shares', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ads-advertise-share-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
