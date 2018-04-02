<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Advertise */

$this->title = 'Update Advertise: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Advertises', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="advertise-update">

    <?= $this->render('_form', [
        'model' => $model,
        'image' => $image,
    ]) ?>

</div>
