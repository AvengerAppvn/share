<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\CriteriaProvince;
use common\models\Bank;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\UserBankSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'User Banks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-bank-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create User Bank', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'account_name',
            'account_number',
            [
                'attribute' => 'bank_id',
                'value' => function ($model) {
                    return $model->bank_id ? $model->bank->name : '';
                },
                'filter' => ArrayHelper::map(Bank::find()->all(), 'id', 'name'),
            ],
            [
                'attribute' => 'province_id',
                'value' => function ($model) {
                    return $model->province_id ? $model->province->name : '';
                },
                'filter' => ArrayHelper::map(CriteriaProvince::find()->all(), 'id', 'name'),
            ],
            'branch_name',
            // 'status',
            // 'created_at',
            // 'updated_at',
            // 'created_by',
            // 'updated_by',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
