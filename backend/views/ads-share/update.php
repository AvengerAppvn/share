<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\AdsShare */

$this->title = 'Update Ads Share: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ads Shares', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ads-share-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
