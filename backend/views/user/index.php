<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Danh sách người dùng');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <p>
        <?php echo Html::a(Yii::t('backend', 'Tạo {modelClass}', [
            'modelClass' => 'Người dùng',
        ]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => [
            'class' => 'grid-view table-responsive'
        ],
        'columns' => [
            [
                'attribute' => 'id',
                'headerOptions' => ['style' => 'width:50px'],
            ],
            'username',
            'email:email',
            'phone',
            [
                'attribute' => 'is_customer',
                'format' => 'html',
                'value' => function ($model) {
                    return $model->is_customer ? Html::tag("i", '', ['class' => 'fa fa-check text-success']) : Html::tag("i", '', ['class' => 'fa fa-close text-danger']);
                },
            ],
            [
                'attribute' => 'is_advertiser',
                'label' => 'Nhà QC',
                'format' => 'html',
                'value' => function ($model) {
                    return $model->is_advertiser ? Html::tag("i", '', ['class' => 'fa fa-check text-success']) : Html::tag("i", '', ['class' => 'fa fa-close text-danger']);
                },
            ],
            'created_at:datetime',
            'logged_at:datetime',
            // 'updated_at',

            [
                'label' => 'Trạng thái',
                'format' => 'html',
                'value' => function ($model) {
                    return $model->is_confirmed == 1 ?
                        Html::a("Kiểm tra", ['confirm', 'id' => $model->id], ['target' => '_blank', 'class' => 'btn btn-success']) :
                        Html::tag("i", ' Đã xác thực', ['class' => 'fa fa-check text-success']);
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'options' => [
                    'style' => 'width:80px;',
                ]
            ]
        ],
    ]); ?>

</div>
