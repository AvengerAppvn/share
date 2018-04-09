<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\UserBank */

$this->title = 'Create User Bank';
$this->params['breadcrumbs'][] = ['label' => 'User Banks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-bank-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
