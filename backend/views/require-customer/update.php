<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RequireCustomer */

$this->title = 'Update Require Customer: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Require Customers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="require-customer-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
