<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CriteriaSpeciality */

$this->title = 'Update Criteria Speciality: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Criteria Specialities', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="criteria-speciality-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
