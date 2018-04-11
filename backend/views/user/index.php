<?php

use common\grid\EnumColumn;
use common\models\User;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php echo Html::a(Yii::t('backend', 'Create {modelClass}', [
            'modelClass' => 'User',
        ]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <div class="count">
        <table class="table table-bordered table-hover dataTable">
            <thead>
            <tr>
                <th>Tài khoản Khách hàng</th>
                <th>Tài khoản Người Quảng cáo</th>
                <th>Tài khoản Khách hàng + Người quảng cáo</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><?php echo $customer; ?></td>
                <td><?php echo $advertiser; ?></td>
                <td><?php echo $user; ?></td>
            </tr>
            </tbody>
        </table>
    </div>
    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => [
            'class' => 'grid-view table-responsive'
        ],
        'columns' => [
            'id',
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

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
