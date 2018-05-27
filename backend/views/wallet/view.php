<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Wallet */

$this->title = 'Tài khoản: '.$model->user->username;
$this->params['breadcrumbs'][] = ['label' => 'Danh sách Ví', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wallet-view">
    <div class="col-md-12">
        <h3>Tài khoản: <?= $model->user_id ? $model->user->username : ''; ?> | Số dư: <?= number_format($model->amount,0,',','.'); ?></h3>
    </div>

    <div class="col-md-12 transaction">
        <h3 class="title-wallet">Lịch Sử Giao Dịch</h3>
        <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    </div>

    <div class="col-md-12 table-bordered">
        <?php echo $this->render('transaction', ['dataProvider' => $dataProvider]); ?>
    </div>
</div>
