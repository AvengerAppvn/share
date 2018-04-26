<?php

use common\grid\EnumColumn;
use common\models\User;
use yii\helpers\Html;
use yii\grid\GridView;

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
            [
                'attribute' => 'userProfile',
                'lable'=>'Ảnh',
                'format' => 'html',
                'value' => function ($model) {
                    return $model->userProfile->avatar_path ? Html::img(
                        \Yii::$app->glide->createSignedUrl([
                            'glide/index',
                            'path' => $model->userProfile->avatar_path,
                            'w' => 120
                        ], true),
                        ['class' => 'img-rounded pull-left']
                    ) : null;
                },
            ],
            'username',
            'email:email',
            'phone',
            [
                'class' => EnumColumn::className(),
                'attribute' => 'status',
                'enum' => User::statuses(),
                'filter' => User::statuses()
            ],
            'created_at:datetime',
            'logged_at:datetime',
            // 'updated_at',

            [
                'attribute' => '',
                'format' => 'html',
                'value' => function ($model) {
                    return $model->is_confirmed == 1 ? Html::a("Xác thực", ['confirm', 'id' => $model->id], ['target' => '_blank', 'class' => 'btn btn-success']) : '';
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
