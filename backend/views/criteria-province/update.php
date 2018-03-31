<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CriteriaProvince */

$this->title = 'Update Criteria Province: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Criteria Provinces', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="criteria-province-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
