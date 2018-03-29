<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\AdsAdvertiseImage */

$this->title = 'Create Ads Advertise Image';
$this->params['breadcrumbs'][] = ['label' => 'Ads Advertise Images', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ads-advertise-image-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
