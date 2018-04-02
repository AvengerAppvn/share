<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Advertise */

$this->title = 'Create Advertise';
$this->params['breadcrumbs'][] = ['label' => 'Advertises', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="advertise-create">

    <?= $this->render('_form', [
        'model' => $model,
        'image' => $image,
    ]) ?>

</div>
