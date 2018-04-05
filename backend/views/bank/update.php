<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Bank */

$this->title = 'Update Bank1: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Bank1s', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="bank-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
