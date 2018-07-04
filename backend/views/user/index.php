<?php

use common\components\helper\CUtils;
use kartik\export\ExportMenu;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Danh sách người dùng');
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="user-index">
    <div class="row">
        <div class="col-md-12">

            <?php
            $gridColumns = [
                ['class' => 'yii\grid\SerialColumn'],
                //'id',
                [
                    'label' => 'Họ và tên',
                    'value' => function ($model) {
                        return $model->userProfile ? $model->userProfile->fullname : $model->username;
                    },
                ],
                'phone',
                'email',
                [
                    'label' => 'Địa chỉ',
                    'value' => function ($model) {
                        return $model->userProfile ? $model->userProfile->address : '';
                    },
                ],
                [
                    'label' => 'Thế mạnh',
                    'value' => function ($model) {
                        //$model->userProfile->strengths;
                        return $model->userProfile ? $model->userProfile->strengths : '';
                    },
                ],
                //['class' => 'yii\grid\ActionColumn'],
            ];

            // Renders a export dropdown menu
            // echo ExportMenu::widget([
            //    'dataProvider' => $dataProvider,
            //    'columns' => $gridColumns,
           // ]);
            ?>
            <?php echo Html::a(Yii::t('backend', 'Tạo {modelClass}', [
                'modelClass' => 'Người dùng',
            ]), ['create'], ['class' => 'btn btn-success']) ?>
        </div>
    </div>


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
                'attribute' => 'status_confirmed',
                'label' => 'Trạng thái',
                'format' => 'html',
                'value' => function ($model) {
                    if (1 == $model->is_confirmed) {
                        return Html::tag("i", ' Đã xác minh', ['class' => 'fa fa-check text-success']);
                    };
                    if (2 == $model->is_confirmed) {
                        return Html::a("Xác minh", ['confirm', 'id' => $model->id], ['target' => '_blank', 'class' => 'btn btn-success']);
                    }
                    return Html::tag("i", ' Chưa xác minh', ['class' => 'fa fa-check text-danger']);
                },
                'filter' => ArrayHelper::map(CUtils::statusUser(), 'id', 'name'),
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
