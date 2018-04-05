<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Bank1 */

$this->title = 'Create Bank';
$this->params['breadcrumbs'][] = ['label' => 'Bank1s', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bank-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
