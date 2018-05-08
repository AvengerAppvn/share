<?php

namespace frontend\modules\api\v1\controllers;

use common\models\Transaction;
use common\models\User;
use common\models\Wallet;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
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
                'deposit' => ['post'],
                'info' => ['get'],
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
        $behaviors['authenticator']['except'] = ['options',];


        // setup access
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['index', 'deposit', 'info'], //only be applied to
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['index', 'deposit', 'info'],
                    'roles' => ['admin', 'manageUsers'],
                ],
                [
                    'allow' => true,
                    'actions' => ['index', 'deposit', 'info'],
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
            $coin = 0;
            $wallet = Wallet::find()->where(['user_id' => $user->id])->one();
            if ($wallet) {
                $coin = doubleval($wallet->amount);
            }
            return array(
                'user_id' => $user->id,
                'coin' => $coin,
            );
        } else {
            // Validation error
            throw new NotFoundHttpException("Object not found");
        }
    }

    // Khi người dùng chọn nạp tiền, hiển thị số tài khoản của admin và form thông báo cho người quản trị
    public function actionDeposit()
    {
        $user = User::findIdentity(\Yii::$app->user->getId());

        if ($user) {
            $response = \Yii::$app->getResponse();
            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->amount = 0;
            $transaction->type = Transaction::TYPE_PENDING;

            if ($this->image) {
                // requires php5
                define('UPLOAD_DIR', \Yii::getAlias('@storage') . '/web/source/capture/');
                $fileStorage = Instance::ensure('fileStorage', Storage::className());

                $img = $this->image;
                $img = str_replace('data:image/png;base64,', '', $img);
                $img = str_replace(' ', '+', $img);
                $data = base64_decode($img);

                $filename = uniqid() . '.png';
                $file = UPLOAD_DIR . $filename;
                $success = file_put_contents($file, $data);

                $transaction->image_base_url = $success ? $fileStorage->baseUrl : '';
                $transaction->image_path = $success ? 'shares/' . $filename : '';
            }

            $transaction->description = 'Yêu cầu nạp nạp tiền';
            $transaction->status = 1;
            $transaction->save();
            $response->setStatusCode(200);

            return $transaction;
        } else {
            // Validation error
            throw new NotFoundHttpException("Object not found");
        }
    }

    public function actionInfo()
    {
        $user = User::findIdentity(\Yii::$app->user->getId());
        return \Yii::t('frontend', \Yii::$app->keyStorage->get('config.admin-bank', 'Tài khoản ngân hàng và cú pháp nạp tiền vào tài khoản {user}'), [
                        'user'=> $user->user_id
                    ]);
    }

    public function actionOptions($id = null)
    {
        return "ok";
    }

}
