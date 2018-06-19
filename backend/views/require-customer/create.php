<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\RequireCustomer */

$this->title = 'Create Require Customer';
$this->params['breadcrumbs'][] = ['label' => 'Require Customers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="require-customer-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
