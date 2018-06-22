<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\base\SystemLog */

$this->title = Yii::t('app', 'Error #{id}', ['id'=>$model->id]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'System Log Endpoints'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="system-log-view">

    <p>
        <?php echo Html::a(Yii::t('app', 'Delete'), ['delete', 'id'=>$model->id], ['class' => 'btn btn-danger', 'data'=>['method'=>'post']]) ?>
    </p>

    <?php echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'action',
            'method',
            [
                'attribute'=>'param',
                'format'=>'raw',
                'value'=>Html::tag('pre', $model->param, ['style'=>'white-space: pre-wrap'])
            ],
            [
                'attribute'=>'header',
                'format'=>'raw',
                'value'=>Html::tag('pre', $model->header, ['style'=>'white-space: pre-wrap'])
            ],
            [
                'attribute' => 'created_at',
                'format' => 'datetime',
                'value' => (int) $model->created_at
            ],
            [
                'attribute'=>'result',
                'format'=>'raw',
                'value'=>Html::tag('pre', $model->result, ['style'=>'white-space: pre-wrap'])
            ],
            'count_time',
            [
                'attribute' => 'updated_at',
                'format' => 'datetime',
                'value' => (int) $model->updated_at
            ],
        ],
    ]) ?>

</div>
