<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CriteriaAge */

$this->title = 'Update Criteria Age: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Criteria Ages', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="criteria-age-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
