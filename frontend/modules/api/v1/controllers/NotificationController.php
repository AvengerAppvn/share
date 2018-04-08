<?php

namespace frontend\modules\api\v1\controllers;

use common\models\Notification;
use common\models\User;
use frontend\modules\api\v1\resources\User as UserResource;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;

/**
 * @author Eugene Terentev <eugene@terentev.net>
 */
class NotificationController extends ActiveController
{
    /**
     * @var string
     */
    public $modelClass = 'frontend\modules\api\v1\resources\Notification';

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
                'remove' => ['delete'],
                'remove-all' => ['delete'],
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

        $user = User::findIdentity(\Yii::$app->user->getId());

        $response = \Yii::$app->getResponse();
        $response->setStatusCode(200);

        $notifications = Notification::find()->where(['user_id' => $user->id])->limit($page_size)->offset($index)->all();
        $notificationsResult = [];

        foreach ($notifications as $notification) {

            $notificationsResult[] = array(
                'id' => $notification->id,
                'name' => $notification->title,
                'createa_at' => $notification->created_at,

            );
        }
        return $notificationsResult;
    }

    public function actionView()
    {
        $response = \Yii::$app->getResponse();
        // ads_id
        $id = Yii::$app->request->get('id');
        if (!$id) {
            $response->setStatusCode(422);
            return 'Thiếu tham số id';
        }

        $notification = Notification::findOne($id);
        if (!$notification) {
            $response->setStatusCode(404);
            return 'Không có dữ liệu với id=' . $id;
        }

        $response->setStatusCode(200);

        // $user = User::findOne($advertise->created_by);
//        $customer_avatar = null;
//        $customer_name = null;
//        if($user){
//            $customer_avatar = $user->userProfile->avatar;
//            $customer_name = $user->userProfile->fullname;
//        }
        return array(
            'id' => $notification->id,
            'title' => $notification->title,
            'description' => $notification->description,
            'created_at' => date('Y-m-d H:i:s', $notification->created_at),
        );
    }

    public function actionRemove()
    {
        $response = \Yii::$app->getResponse();
        // $id
        $id = Yii::$app->request->get('id');
        if (!$id) {
            $response->setStatusCode(422);
            return array(
                'name' => 'Thiếu tham số',
                'message' => 'Thiếu tham số id',
                'code' => 0,
                'status' => 422,
            );
        }


        $notification = Notification::findOne($id);
        if (!$notification) {
            $response->setStatusCode(404);
            return array(
                'name' => 'Không có dữ liệu',
                'message' => 'Không tìm dược dữ liệu với id=' . $id,
                'code' => 0,
                'status' => 404,
            );
        }

        $response->setStatusCode(200);
        $id = $notification->id;
        $result = $notification->delete();
        return array(
            'id' => $id,
            'status' => $result,
        );
    }

    public function actionRemoveAll()
    {
        $user = User::findIdentity(\Yii::$app->user->getId());
        if ($user) {
            $result = Notification::deleteAll(['user_id' => $user->id]);
            $response = \Yii::$app->getResponse();
            return array(
                'status' => $result,
            );
        } else {
            // Validation error
            throw new NotFoundHttpException("Object not found");
        }
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
