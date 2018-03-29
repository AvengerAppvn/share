<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\CriteriaAge */

$this->title = 'Create Criteria Age';
$this->params['breadcrumbs'][] = ['label' => 'Criteria Ages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="criteria-age-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
