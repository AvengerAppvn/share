<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\AdsShare */

$this->title = 'Create Ads Share';
$this->params['breadcrumbs'][] = ['label' => 'Ads Shares', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ads-share-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
