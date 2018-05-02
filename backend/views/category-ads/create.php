<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\CategoryAds */

$this->title = Yii::t('backend', 'Create {modelClass}', [
    'modelClass' => 'Category Ads',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Category Ads'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-ads-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
