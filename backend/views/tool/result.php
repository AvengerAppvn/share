<?php
/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $categories common\models\ArticleCategory[] */
use yii\helpers\Html;
$this->title = 'Result';
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Result'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tool-result">

    <h1>Giống nhau tới: <?php echo $result ?> %</h1>
    <p>
        <?= Html::a('So sánh tiếp', ['compare'], ['class' => 'btn btn-success']) ?>
    </p>
</div>
