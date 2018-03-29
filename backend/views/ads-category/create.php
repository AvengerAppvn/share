<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\AdsCategory */

$this->title = 'Create Ads Category';
$this->params['breadcrumbs'][] = ['label' => 'Ads Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ads-category-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
