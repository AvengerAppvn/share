<?php

namespace backend\controllers;

use backend\models\search\Dashboard1Search;
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
 * DashboardController implements the CRUD actions for User model.
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
        $customer = count(User::find()->where(['is_customer' => 1, 'is_advertiser' => !1])->all());
        $advertiser = count(User::find()->where(['is_advertiser' => 1, 'is_customer' => !1])->all());
        $total_user = count(User::find()->all());
        $advertise = Advertise::find()->where(['status' => 1])->orderBy(['id' => SORT_DESC])->limit(5)->all();
        $request = Request::find()->orderBy(['id' => SORT_DESC])->limit(5)->all();
        $count_request = Request::find()->where(['status'=>2,'type'=>2])->count(); // Yeu cau nap tien
        $new_user = User::find()->orderBy(['id' => SORT_DESC])->limit(5)->all();
        $countRequestUser = User::find()->where(['status_confirmed'=>2])->count();

        return $this->render('index', [
            'customer' => $customer,
            'advertiser' => $advertiser,
            'count_request' => $count_request,
            'total_user' => $total_user,
            'advertise' => $advertise,
            'request' => $request,
            'new_user' => $new_user,
            'count_request_user' => $countRequestUser,
        ]);
    }
    public function actionCustomer()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->searchCustomer(Yii::$app->request->queryParams);

        return $this->render('list_user', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAdvertiser()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->searchAdvertiser(Yii::$app->request->queryParams);

        return $this->render('list_user', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionListUser()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->searchListUser(Yii::$app->request->queryParams);

        return $this->render('list_user', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

//    public function actionListUser($is_customer = null, $is_advertiser = null)
//    {
//        if ($is_customer) {
//            if ($is_advertiser) {
//                $users = User::find()->where(['is_customer' => $is_customer, 'is_advertiser' => $is_advertiser])->all();
//            } else {
//                $users = User::find()->where(['is_customer' => $is_customer])->all();
//            }
//        } else {
//            $users = User::find()->where(['is_advertiser' => $is_advertiser])->all();
//        }
//
//        return $this->render('list_user', [
//            'users' => $users,
//        ]);
//    }
}
