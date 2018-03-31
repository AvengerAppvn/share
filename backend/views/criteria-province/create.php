<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\CriteriaProvince */

$this->title = 'Create Criteria Province';
$this->params['breadcrumbs'][] = ['label' => 'Criteria Provinces', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="criteria-province-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
