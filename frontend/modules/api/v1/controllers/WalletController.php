<?php

namespace frontend\modules\api\v1\controllers;

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
            $strengths = ['Thời trang', 'Điện tử', 'Du lịch'];
            $coin = 0;
            return array(
                'fullname' => $user->userProfile->fullname,
                'address' => $user->userProfile->address ?: '',
                'email' => $user->email,
                'phone' => $user->phone,
                'avatar' => $user->userProfile->avatar ?: '',
                'is_confirmed' => $user->is_confirmed ?: false,
                'birthday' => $user->userProfile->birthday ?: '',
                'strengths' => $strengths,
                'coin' => $coin,
                'is_customer' => $user->is_customer? true : false,
                'is_advertiser' => $user->is_advertiser? true : false,
                'status_confirmed' => $user->status_confirmed,
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
