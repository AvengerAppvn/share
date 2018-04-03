<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Wallet */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Wallets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wallet-view">
    <div class="col-md-12">
        <h3> User: <?= $model->user_id ? $model->user->username : ''; ?></h3>
        <h3>Số dư: <?= $model->amount; ?></h3>
    </div>

    <div class="col-md-12 transaction">
        <h3 class="title-wallet">Chọn Thời gian Giao Dịch: </h3>
        <?php echo $this->render('_search', ['model' => $searchModel, 'user_id' => $model->user_id]); ?>
    </div>

    <div class="col-md-12 transaction">
        <h3 class="title-wallet">Lịch Sử Giao Dịch</h3>
        <?php echo $this->render('transaction', ['dataProvider' => $dataProvider]); ?>
    </div>
</div>
