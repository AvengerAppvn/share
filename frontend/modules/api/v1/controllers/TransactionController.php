<?php

namespace frontend\modules\api\v1\controllers;

use common\models\Transaction;
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
class TransactionController extends ActiveController
{
    /**
     * @var string
     */
    public $modelClass = 'frontend\modules\api\v1\resources\Transaction';

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
                'add' => ['get'],
                'sub' => ['get'],
                'pending' => ['get'],
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
        $behaviors['authenticator']['except'] = ['options'];


        // setup access
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['index','add', 'sub', 'pending'], //only be applied to
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['index','add', 'sub', 'pending'],
                    'roles' => ['admin', 'manageUsers'],
                ],
                [
                    'allow' => true,
                    'actions' => ['index','add', 'sub', 'pending'],
                    'roles' => ['@']
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
        $type = \Yii::$app->request->get('type');
        if (!$type) {
            $type = 1;
        }
        return $this->getList($page_size, $index, $type);
    }

    public function actionAdd()
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

    public function actionSub()
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

        return $this->getList($page_size, $index, 2);
    }

    public function actionPending()
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

        return $this->getList($page_size, $index, 3);
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

        $transaction = Transaction::findOne($id);
        if (!$transaction) {
            $response->setStatusCode(404);
            return 'Không có dữ liệu với id=' . $id;
        }

        $response->setStatusCode(200);

        return array(
            'id' => $transaction->id,
            'description' => $transaction->description,
            'ads_id' => $transaction->ads_id,
            'created_at' => date('Y-m-d H:i:s', $transaction->created_at),
        );
    }

    public function actionRemove()
    {
        $response = \Yii::$app->getResponse();
        // $id
        $id = \Yii::$app->request->post('id');
        if (!$id) {
            $response->setStatusCode(422);
            return array(
                'name' => 'Thiếu tham số',
                'message' => 'Thiếu tham số id',
                'code' => 0,
                'status' => 422,
            );
        }


        $transaction = Transaction::findOne($id);
        if (!$transaction) {
            $response->setStatusCode(404);
            return array(
                'name' => 'Không có dữ liệu',
                'message' => 'Không tìm dược dữ liệu với id=' . $id,
                'code' => 0,
                'status' => 404,
            );
        }

        $response->setStatusCode(200);
        $id = $transaction->id;
        $result = $transaction->delete();
        return array(
            'id' => $id,
            'status' => $result,
        );
    }

    public function actionRemoveAll()
    {
        $user = User::findIdentity(\Yii::$app->user->getId());
        if ($user) {
            $result = Transaction::deleteAll(['user_id' => $user->id]);
            $response = \Yii::$app->getResponse();
            return array(
                'count_deleted' => $result,
            );
        } else {
            // Validation error
            throw new NotFoundHttpException("Object not found");
        }
    }

    public function generate()
    {
        $users = User::find()->all();
        foreach ($users as $user) {
            for ($i = 0; $i < 10; $i++) {
                $transaction = new Transaction();
                $type = rand(1,3);
                if($type == 1){
                    $str = 'Thu';
                }elseif($type == 2){
                    $str = 'Chi';
                }else{
                    $str = 'Đang chờ';
                }
                $transaction->description = "Mô tả giao dịch " . $str;
                $transaction->user_id = $user->id;
                $transaction->type = $type;
                $transaction->save();
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

        $transactions = Transaction::find()->where(
            [
                'user_id' => $user->id,
                'type' => $type
            ])
            ->limit($page_size)
            ->offset($index)
            ->all();
        $transactionsResult = [];

        foreach ($transactions as $transaction) {

            $transactionsResult[] = array(
                'id' => $transaction->id,
                'description' => $transaction->description,
                'amount' => $transaction->amount,
                'created_at' => date('Y-m-d H:i:s', $transaction->created_at),

            );
        }
        return $transactionsResult;
    }
}
