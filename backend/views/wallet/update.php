<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Wallet */

$this->title = 'Cập nhật Ví: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Danh sách Ví', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="wallet-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
