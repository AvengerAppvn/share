<?php
/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $categories common\models\ArticleCategory[] */

$this->title = Yii::t('backend', 'Tool');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Tool'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tool-compare">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
