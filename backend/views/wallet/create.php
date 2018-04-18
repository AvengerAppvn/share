<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Wallet */

$this->title = 'Tạo Ví';
$this->params['breadcrumbs'][] = ['label' => 'Danh sách Ví', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wallet-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
