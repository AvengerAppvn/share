<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CategoryAds */

$this->title = Yii::t('backend', 'Update {modelClass}: ', [
    'modelClass' => 'Category Ads',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Category Ads'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="category-ads-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
