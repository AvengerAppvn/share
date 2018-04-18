<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\AdsCategory */

$this->title = 'Tạo Danh mục';
$this->params['breadcrumbs'][] = ['label' => 'Danh sách danh mục', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ads-category-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
