<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Time */

$this->title = 'Create Time';
$this->params['breadcrumbs'][] = ['label' => 'Times', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="time-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
