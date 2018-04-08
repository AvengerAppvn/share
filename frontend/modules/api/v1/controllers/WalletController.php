<?php

namespace frontend\modules\api\v1\controllers;

use common\models\Transaction;
use frontend\models\UserEditForm;
use backend\models\LoginForm;
use common\models\User;
use frontend\modules\api\v1\resources\User as UserResource;
use frontend\modules\user\models\SignupConfirmForm;
use frontend\modules\user\models\SignupForm;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\Url;
use yii\rest\ActiveController;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

/**
 * @author Eugene Terentev <eugene@terentev.net>
 */
class WalletController extends ActiveController
{
    /**
     * @var string
     */
    public $modelClass = 'frontend\modules\api\v1\resources\User';

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);

    }

    public function actions()
    {
        return [];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                HttpBearerAuth::className(),
            ],

        ];

        $behaviors['verbs'] = [
            'class' => \yii\filters\VerbFilter::className(),
            'actions' => [
                'index' => ['get'],
                'transact' => ['get'],
                'history' => ['get'],
            ],
        ];

        // remove authentication filter
        $auth = $behaviors['authenticator'];
        unset($behaviors['authenticator']);

        // add CORS filter
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
            ],
        ];

        // re-add authentication filter
        $behaviors['authenticator'] = $auth;
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options', 'login', 'signup', 'confirm', 'password-reset-request', 'password-reset-token-verification', 'password-reset'];


        // setup access
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['index', 'transact', 'history'], //only be applied to
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['index', 'transact', 'history'],
                    'roles' => ['admin', 'manageUsers'],
                ],
                [
                    'allow' => true,
                    'actions' => ['index', 'transact', 'history'],
                    'roles' => ['user']
                ]
            ],
        ];

        return $behaviors;
    }

    /**
     * Rest Description: Your endpoint description.
     * Rest Fields: ['field1', 'field2'].
     * Rest Filters: ['filter1', 'filter2'].
     * Rest Expand: ['expandRelation1', 'expandRelation2'].
     */
    public function actionIndex()
    {
        $user = User::findIdentity(\Yii::$app->user->getId());

        if ($user) {
            $response = \Yii::$app->getResponse();
            $response->setStatusCode(200);
            // TODO get from profile
            $coin = 6900000;
            return array(
                'user_id' => $user->id,
                'coin' => $coin,
            );
        } else {
            // Validation error
            throw new NotFoundHttpException("Object not found");
        }
    }

    public function actionTransact()
    {
        $user = User::findIdentity(\Yii::$app->user->getId());

        if ($user) {

            $page_size = Yii::$app->request->get('page_size');
            $page_index = Yii::$app->request->get('page_index');
            if (!$page_size) {
                $page_size = 8;
            }

            if (!$page_index) {
                $page_index = 1;
            }

            $index = $page_size * ($page_index - 1);

            $response = \Yii::$app->getResponse();

            $response->setStatusCode(200);

            $transactions = Transaction::find()->where(['user_id' => $user->id])->limit($page_size)->offset($index)->all();

            $advertisesResult = [];

            foreach ($transactions as $transaction) {
                $user = User::findOne($transaction->created_by);
                $customer_avatar = null;
                $customer_name = null;
                if($user){
                    $customer_avatar = $user->userProfile->avatar;
                    $customer_name = $user->userProfile->fullname;
                }
                $advertisesResult[] = array(
                    'id' => $transaction->id,
                    'title' => $transaction->title,
                    'description' => $transaction->description,
                    'thumbnail' => $transaction->thumb,
                    'created_at' => date('Y-m-d H:i:s',$transaction->created_at),
                    'customer_avatar' => $customer_avatar?:'',
                    'customer_name' => $customer_name?:'',
                );
            }
            return $advertisesResult;
        } else {
            // Validation error
            throw new NotFoundHttpException("Object not found");
        }
    }

    public function actionHistory()
    {
        $user = User::findIdentity(\Yii::$app->user->getId());

        if ($user) {

            $model = new UserEditForm();
            $model->load(\Yii::$app->request->post(), '');
            $model->id = $user->id;

            if ($model->validate() && $model->save()) {
                $response = \Yii::$app->getResponse();
                $response->setStatusCode(200);
                $user = $model->getUserByID();
                return [
                    'fullname' => $user->userProfile->fullname,
                    'address' => $user->userProfile->address,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    //'avatar' => $user->userProfile->avatar,
                    'birthday' => $user->userProfile->birthday,
                    'strengths' => json_decode($user->userProfile->strengths),
                    //'last_login_at' =>  $user->last_login_at,
                    //'last_login_ip' =>  $user->last_login_ip,
                ];
            } else {
                // Validation error
                throw new HttpException(422, json_encode($model->errors));
            }
        } else {
            // Validation error
            throw new NotFoundHttpException("Object not found");
        }
    }

    public function actionOptions($id = null)
    {
        return "ok";
    }

}
