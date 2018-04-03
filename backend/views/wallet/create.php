<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Wallet */

$this->title = 'Create Wallet';
$this->params['breadcrumbs'][] = ['label' => 'Wallets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wallet-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
