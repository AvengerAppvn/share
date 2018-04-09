<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\UserBank */

$this->title = 'Update User Bank: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'User Bank1s', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-bank-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
