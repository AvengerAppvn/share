<?php

namespace frontend\modules\api\v1\controllers;

use common\models\Request;
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
class HistoryController extends ActiveController
{
    /**
     * @var string
     */
    public $modelClass = 'frontend\modules\api\v1\resources\Request';

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
                'deposit' => ['get'],
                'withdraw' => ['get'],
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
            'only' => ['index','deposit', 'withdraw'], //only be applied to
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['index','deposit', 'withdraw'],
                    'roles' => ['admin', 'manageUsers'],
                ],
                [
                    'allow' => true,
                    'actions' => ['index','deposit', 'withdraw'],
                    'roles' => ['@']
                ]
            ],
        ];

        return $behaviors;
    }

    public function actionIndex()
    {
        //TODO remove
        $exist = Request::find()->one();
        if (!$exist) {
            $this->generate();
        }

        $page_size = \Yii::$app->request->get('page_size');
        $page_index = \Yii::$app->request->get('page_index');
        if (!$page_size) {
            $page_size = 8;
        }

        if (!$page_index) {
            $page_index = 1;
        }

        $index = $page_size * ($page_index - 1);
        $type = \Yii::$app->request->get('type');
        if (!$type) {
            $type = 1;
        }
        return $this->getList($page_size, $index, $type);
    }

    public function actionDeposit()
    {
        //TODO remove
        $exist = Request::find()->one();
        if (!$exist) {
            $this->generate();
        }

        $page_size = \Yii::$app->request->get('page_size');
        $page_index = \Yii::$app->request->get('page_index');
        if (!$page_size) {
            $page_size = 8;
        }

        if (!$page_index) {
            $page_index = 1;
        }

        $index = $page_size * ($page_index - 1);

        return $this->getList($page_size, $index, 2);
    }

    public function actionWithdraw()
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

        return $this->getList($page_size, $index, 1);
    }

    public function actionDetail()
    {
        $response = \Yii::$app->getResponse();
        // ads_id
        $id = \Yii::$app->request->get('id');
        if (!$id) {
            $response->setStatusCode(422);
            return 'Thiếu tham số id';
        }

        $request = Request::findOne($id);
        if (!$request) {
            $response->setStatusCode(404);
            return 'Không có dữ liệu với id=' . $id;
        }

        $response->setStatusCode(200);

        return array(
            'id' => $request->id,
            'description' => $request->description,
            'created_at' => date('Y-m-d H:i:s', $request->created_at),
        );
    }

    public function generate()
    {
        $users = User::find()->all();
        foreach ($users as $user) {
            for ($i = 0; $i < 10; $i++) {

                $type = rand(1,2);
                if($type == 1){
                    $str = 'Rút';
                }else{
                    $str = 'Nạp';
                }

                $request = new Request();
                $request->description = "Mô tả lịch sử ". $str;
                $request->user_id = $user->id;
                $request->amount = 10000;
                $request->type = $type;
                $request->save();
            }
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

    /**
     * @param $page_size
     * @param $index
     * @return array
     */
    private function getList($page_size, $index, $type = 1)
    {
        $user = User::findIdentity(\Yii::$app->user->getId());

        $response = \Yii::$app->getResponse();
        $response->setStatusCode(200);

        $requests = Request::find()->where(
            [
                'user_id' => $user->id,
                'type' => $type
            ])
            ->limit($page_size)
            ->offset($index)
            ->all();
        $requestsResult = [];

        foreach ($requests as $request) {

            $requestsResult[] = array(
                'id' => $request->id,
                'description' => $request->description,
                'amount' => $request->amount,
                'created_at' => date('Y-m-d H:i:s', $request->created_at),

            );
        }
        return $requestsResult;
    }
}
