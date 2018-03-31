<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\CriteriaSpeciality */

$this->title = 'Create Criteria Speciality';
$this->params['breadcrumbs'][] = ['label' => 'Criteria Specialities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="criteria-speciality-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
