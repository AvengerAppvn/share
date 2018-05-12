<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\History */

$this->title = 'Update History: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Histories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="history-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
