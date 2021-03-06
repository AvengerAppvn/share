<?php

namespace frontend\modules\api\v1\controllers;

use common\models\Bank;
use common\models\User;
use common\models\UserBank;
use frontend\models\BankForm;
use frontend\modules\api\v1\resources\User as UserResource;
use yii\base\ErrorException;
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
                'me' => ['get'],
                'add' => ['post'],
                'remove' => ['delete'],
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
            'only' => ['index', 'view', 'add', '', 'me'], //only be applied to
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['index', 'view', 'add', 'remove', 'me'],
                    'roles' => ['admin', 'manageUsers'],
                ],
                [
                    'allow' => true,
                    'actions' => ['index', 'view', 'add', 'remove', 'me'],
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

        $response = \Yii::$app->getResponse();
        $response->setStatusCode(200);

        $banks = Bank::find()->limit($page_size)->offset($index)->all();
        $banksResult = [];

        foreach ($banks as $bank) {
            try {
                list($width, $height) = getimagesize(\Yii::getAlias('@storage') . '/web/source/' . $bank->thumbnail_path);
            } catch (ErrorException $e) {
                $width = $height = 0;
            }
            $banksResult[] = array(
                'id' => $bank->id,
                'name' => $bank->name,
                'fee_bank' => $bank->fee_bank ?: 0,
                'description' => $bank->description ?: '',
                'logo' => $bank->thumb ?: '',
                'logo_width' => $width,
                'logo_height' => $height,
            );
        }
        return $banksResult;
    }

    public function actionAdd()
    {
        $user = User::findIdentity(\Yii::$app->user->getId());
        $model = new BankForm();

        $model->load(\Yii::$app->getRequest()->getBodyParams(), '');
        $model->user_id = $user->id;
        if ($model->validate() && ($user_bank = $model->save())) {
            $response = \Yii::$app->getResponse();
            $response->setStatusCode(200);
            $response->getHeaders()->set('Location', Url::toRoute([$user_bank->id], true));
            return array(
                'account_name' => $user_bank->account_name,
                'account_number' => $user_bank->account_number,
                'bank_name' => $user_bank->bank_name,
                'province_name' => $user_bank->province_name,
                'branch_name' => $user_bank->branch_name,
                'created_at' => date('Y-m-d H:i:s', $user_bank->created_at),
            );
        } else {
            // Validation error
            $message = '';
            foreach ($model->errors as $error) {
                $message .= $error[0];
            }
            throw new HttpException(422, $message);
        }


    }

    public function actionRemove()
    {
        $response = \Yii::$app->getResponse();
        $user = User::findIdentity(\Yii::$app->user->getId());
        $user_bank = UserBank::find()->where(['user_id' => $user->id])->one();

        if (!$user_bank) {
            $response->setStatusCode(404);
            return array(
                'name' => 'Không có dữ liệu',
                'message' => 'Không tìm dược dữ liệu ',
                'code' => 0,
                'status' => 404,
            );
        }

        $response->setStatusCode(200);
        $id = $user_bank->id;
        $result = $user_bank->delete();
        return array(
            'account_name' => $user_bank->account_name,
            'account_number' => $user_bank->account_number,
            'status' => $result,
        );
    }

    public function actionMe()
    {
        $response = \Yii::$app->getResponse();
        $user = User::findIdentity(\Yii::$app->user->getId());
        $user_bank = UserBank::find()->where(['user_id' => $user->id])->one();
        if (!$user_bank) {
            $response->setStatusCode(404);
            return array(
                'name' => 'Không có dữ liệu',
                'message' => 'Chưa có ngân hàng nào',
                'code' => 0,
                'status' => 404,
            );
        }

        $response->setStatusCode(200);
        try {
            list($width, $height) = getimagesize(\Yii::getAlias('@storage') . '/web/source/' . $user_bank->bank->thumbnail_path);
        } catch (ErrorException $e) {
            $width = $height = 0;
        }
        return array(
            'account_name' => $user_bank->account_name,
            'account_number' => $user_bank->account_number,
            'bank_name' => $user_bank->bank_name,
            'province_name' => $user_bank->province_name,
            'branch_name' => $user_bank->branch_name,
            'created_at' => date('Y-m-d H:i:s', $user_bank->created_at),
            'logo' => $user_bank->bank ? $user_bank->bank->thumb : '',
            'logo_width' => $width,
            'logo_height' => $height,
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
