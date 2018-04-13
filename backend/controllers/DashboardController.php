<?php

namespace backend\controllers;

use common\models\Advertise;
use common\models\Request;
use common\models\search\AdvertiseSearch;
use Yii;
use common\models\User;
use backend\models\UserForm;
use backend\models\search\UserSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class DashboardController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $customer = count(User::find()->where(['is_customer' => 1])->all());
        $advertiser = count(User::find()->where(['is_advertiser' => 1])->all());
        $user = count(User::find()->where(['is_advertiser' => 1, 'is_customer' => 1])->all());
        $total_user = count(User::find()->all());
        $advertise = Advertise::find()->where(['status' => 1])->orderBy(['id' => SORT_DESC])->limit(5)->all();
        $request = Request::find()->orderBy(['id' => SORT_DESC])->limit(5)->all();
        $new_user  = User::find()->orderBy(['id' => SORT_DESC])->limit(5)->all();

        return $this->render('index', [
            'customer' => $customer,
            'advertiser' => $advertiser,
            'user' => $user,
            'total_user' => $total_user,
            'advertise' => $advertise,
            'request' => $request,
            'new_user' => $new_user,
        ]);
    }

}
