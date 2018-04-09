<?php

namespace frontend\modules\api\v1\controllers;

use common\models\Bank;
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
class BankController extends ActiveController
{
    /**
     * @var string
     */
    public $modelClass = 'frontend\modules\api\v1\resources\UserBank';

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
                    'actions' => ['index', 'view'],
                    'roles' => ['admin', 'manageUsers'],
                ],
                [
                    'allow' => true,
                    'actions' => ['index', 'view'],
                    'roles' => ['user']
                ]
            ],
        ];

        return $behaviors;
    }

    public function actionIndex()
    {
        $page_size = \Yii::$app->request->get('page_size');
        $page_index = \Yii::$app->request->get('page_index');
        if (!$page_size) {
            $page_size = 8;
        }

        if (!$page_index) {
            $page_index = 1;
        }

        $index = $page_size * ($page_index - 1);

        $response = \Yii::$app->getResponse();
        $response->setStatusCode(200);

        $banks = Bank::find()->limit($page_size)->offset($index)->all();
        $banksResult = [];

        foreach ($banks as $bank) {
            $banksResult[] = array(
                'id' => $bank->id,
                'name' => $bank->name,

            );
        }
        return $banksResult;
    }

    public function actionCreate()
    {
        $model = new BankForm();
        $model->load(\Yii::$app->getRequest()->getBodyParams(), '');

        if ($model->validate() && ($ads = $model->save())) {
            $response = \Yii::$app->getResponse();
            $response->setStatusCode(200);
            $response->getHeaders()->set('Location', Url::toRoute([$ads->id], true));
            return array(
                'id'=> $ads->id,
                'title'=> $ads->title,
                'require'=> $ads->content,
                'message'=> $ads->description,
                'cat_id'=> 1,
                'created_at'=> date('Y-m-d H:i:s',$ads->created_at),
                'thumbnail'=> $ads->thumb,
            );
        } else {
            // Validation error
            throw new HttpException(422, json_encode($model->errors));
        }


    }

    public function actionView()
    {
        $response = \Yii::$app->getResponse();
        // ads_id
        $ads_id = Yii::$app->request->get('ads_id');
        if(!$ads_id){
            $response->setStatusCode(422);
            return array(
                'name'=> 'Thiếu tham số',
                'message'=> array('ads_id'=> 'Thiếu tham số ads_id'),
                'code'=> 0,
                'status'=> 422,
            );
        }


        $advertise = Advertise::findOne($ads_id);
        if(!$advertise){
            $response->setStatusCode(404);
            return array(
                'name'=> 'Không có dữ liệu',
                'message'=> array('ads_id'=> 'Không tìm dược dữ liệu với id='.$ads_id),
                'code'=> 0,
                'status'=> 404,
            );
        }

        $response->setStatusCode(200);
        return array(
            'id' => $advertise->id,
            'title' => $advertise->title,
            'description' => $advertise->description,
            'content' => $advertise->content,
            'thumbnail' => $advertise->thumb,
            //'images' => $advertise->advertiseImages,
            'images' => [$advertise->thumb],
            'created_at' => date('Y-m-d H:i:s',$advertise->created_at),
            'share' => $advertise->share ?: 0,
        );
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
