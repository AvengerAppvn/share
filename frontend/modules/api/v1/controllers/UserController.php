<?php

namespace frontend\modules\api\v1\controllers;

use frontend\models\UserEditForm;
use backend\models\LoginForm;
use common\models\User;
use frontend\models\UserVerifyForm;
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
class UserController extends ActiveController
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
                'view' => ['get'],
                'create' => ['post'],
                'update' => ['put'],
                'delete' => ['delete'],
                'login' => ['post'],
                'me' => ['get', 'post'],
                'profile' => ['get', 'post'],
                'emotion' => ['get'],
                'addresses' => ['get'],
                'verify' => ['post'],
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
            'only' => ['index', 'view', 'create', 'update', 'delete', 'me'], //only be applied to
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['index', 'view', 'create', 'update', 'delete'],
                    'roles' => ['admin', 'manageUsers'],
                ],
                [
                    'allow' => true,
                    'actions' => ['me','update'],
                    'roles' => ['user']
                ]
            ],
        ];

        return $behaviors;
    }

//    public function actionCreate()
//    {
//        $model = new User();
//        $model->load(\Yii::$app->getRequest()->getBodyParams(), '');
//
//        if ($model->validate() && $model->save()) {
//            $response = \Yii::$app->getResponse();
//            $response->setStatusCode(201);
//            $id = implode(',', array_values($model->getPrimaryKey(true)));
//            $response->getHeaders()->set('Location', Url::toRoute([$id], true));
//        } else {
//            // Validation error
//            throw new HttpException(422, json_encode($model->errors));
//        }
//
//        return $model;
//    }

    public function actionLogin()
    {
        $model = new LoginForm();
//        $model->load(\Yii::$app->request->post(), '');
//        $response = \Yii::$app->getResponse();
//        $response->setStatusCode(200);
//        return $model;
        if ($model->load(\Yii::$app->request->post(), '') && $model->login()) {
            $user = $model->getUser();
            $user->generateAccessTokenAfterUpdatingClientInfo(true);

            $response = \Yii::$app->getResponse();
            $response->setStatusCode(200);
            $id = implode(',', array_values($user->getPrimaryKey(true)));

            $responseData = [
                'id' => (int)$id,
                'access_token' => $user->access_token,
            ];

            return $responseData;
        } else {
            // Validation error
            throw new HttpException(422, json_encode($model->errors));
        }
    }

    public function actionSignup()
    {
        $model = new SignupForm();

        $model->load(\Yii::$app->request->post(), '');
        $model->username = $model->email;

        if ($model->validate() && ($result = $model->signup())) {
            // Send confirmation email
            $model->sendConfirmationEmail();

            $response = \Yii::$app->getResponse();
            $response->setStatusCode(200);

            $user = User::findOne($result);
            $user->generateAccessTokenAfterUpdatingClientInfo(true);

            $responseData = array(
                'id' => $user->id,
                'access_token' => $user->access_token,
                'email' => $user->email,
                'created_at' => date('Y-m-d H:i:s', $user->created_at),
                //'status' => $user->status,
            );

            return $responseData;
        } else {
            // Validation error
            throw new HttpException(422, json_encode($model->errors));
        }
    }

    public function actionConfirm()
    {
        $model = new SignupConfirmForm();

        $model->load(\Yii::$app->request->post());
        if ($model->validate() && $model->confirm()) {

            $response = \Yii::$app->getResponse();
            $response->setStatusCode(200);

            $user = $model->getUser();
            $responseData = [
                'id' => (int)$user->id,
                'access_token' => $user->access_token,
            ];

            return $responseData;

        } else {
            // Validation error
            throw new HttpException(422, json_encode($model->errors));
        }
    }

    public function actionPasswordResetRequest()
    {
        $model = new PasswordResetRequestForm();

        $model->load(Yii::$app->request->post());
        if ($model->validate() && $model->sendPasswordResetEmail()) {

            $response = \Yii::$app->getResponse();
            $response->setStatusCode(200);

            $responseData = "true";

            return $responseData;
        } else {
            // Validation error
            throw new HttpException(422, json_encode($model->errors));
        }
    }

    public function actionPasswordResetTokenVerification()
    {
        $model = new PasswordResetTokenVerificationForm();

        $model->load(Yii::$app->request->post());
        if ($model->validate() && $model->validate()) {

            $response = \Yii::$app->getResponse();
            $response->setStatusCode(200);

            $responseData = "true";

            return $responseData;
        } else {
            // Validation error
            throw new HttpException(422, json_encode($model->errors));
        }
    }

    /**
     * Resets password.
     */
    public function actionPasswordReset()
    {
        $model = new PasswordResetForm();
        $model->load(Yii::$app->request->post());

        if ($model->validate() && $model->resetPassword()) {

            $response = \Yii::$app->getResponse();
            $response->setStatusCode(200);

            $responseData = "true";

            return $responseData;
        } else {
            // Validation error
            throw new HttpException(422, json_encode($model->errors));
        }
    }

    /**
     * Rest Description: Your endpoint description.
     * Rest Fields: ['field1', 'field2'].
     * Rest Filters: ['filter1', 'filter2'].
     * Rest Expand: ['expandRelation1', 'expandRelation2'].
     */
    public function actionMe()
    {
        $user = User::findIdentity(\Yii::$app->user->getId());

        if ($user) {
            $response = \Yii::$app->getResponse();
            $response->setStatusCode(200);
            // TODO get from profile

            $strengths = [];
            $strengths[] = array(
                'id'=>1,
                'name'=>'Thời trang',
            );
            $strengths[] = array(
                'id'=>2,
                'name'=>'Điện thoại',
            );
            $strengths[] = array(
                'id'=>3,
                'name'=>'Ẩm thực',
            );
            $coin = 123123;
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

    public function actionUpdate()
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
                    'avatar' => $user->userProfile->avatar,
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

    public function actionVerify()
    {
        $user = User::findIdentity(\Yii::$app->user->getId());

        if ($user) {

            $model = new UserVerifyForm();
            $model->load(\Yii::$app->request->post(), '');
            $model->id = $user->id;

            if ($model->validate() && $model->save()) {
                $response = \Yii::$app->getResponse();
                $response->setStatusCode(200);
                $user = $model->getUserByID();
                return [
                    'user_id' => $user->id,
                    'message' => 'Successful',
                    'is_customer' => $user->is_customer,
                    'is_advertiser' => $user->is_advertiser
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

    public function actionLogout()
    {
        $user = User::findIdentity(\Yii::$app->user->getId());

        if ($user) {
            $user->destroyAccessToken();
        }

        $response = \Yii::$app->getResponse();
        $response->setStatusCode(200);

        $responseData = "true";

        return $responseData;
    }

    public function actionOptions($id = null)
    {
        return "ok";
    }

    /**
     * @param $id
     * @return null|static
     * @throws NotFoundHttpException
     */
    public function findModel($id)
    {
        $model = UserResource::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException;
        }
        return $model;
    }
}
