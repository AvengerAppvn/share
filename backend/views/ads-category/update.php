<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\AdsCategory */

$this->title = 'Cập nhật Danh mục: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Danh sách danh mục', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ads-category-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
