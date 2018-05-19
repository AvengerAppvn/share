<?php

namespace backend\controllers;

use common\models\History;
use common\models\Notification;
use common\models\Request;
use common\models\search\RequestSearch;
use common\models\UserDeviceToken;
use common\models\Wallet;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * RequestController implements the CRUD actions for Request model.
 */
class RequestController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Request models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RequestSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort = ['defaultOrder' => ['id' => SORT_DESC]];
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Request model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Request model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Request();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionCheck($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            $model->status = 1;
            if ($model->save()) {

                $wallet = Wallet::find()->where(['user_id' => $model->user_id])->one();
                if ($wallet) {
                    $wallet->amount += $model->amount;
                    $wallet->save();
                } else {
                    $wallet = new Wallet();
                    $wallet->user_id = $model->user_id;
                    $wallet->amount = $model->amount;
                    $wallet->status = 1;
                    $wallet->save();
                }

                $history = new History();

                $history->user_id = $model->user_id;
                $history->amount = $model->amount;
                $history->description = $model->description;
                $history->type = 2; // Nap tien
                $history->status = 1;
                $history->save();

                $notification = new Notification();
                $notification->title = "Bạn đã được chuyển $model->amount vào ví";
                $notification->description = "Hệ thống đã chuyển thành công số tiền $model->amount vào ví của bạn";
                $notification->user_id = $model->user_id;
                $notification->ads_id = 0;
                $notification->save();

                // Notification
                $device = UserDeviceToken::findOne(['user_id'=>$model->user_id]);
                if($device && $device->player_id) {
                    $message = array('en' => 'Hệ thống đã nạp tiền thành công cho bạn');
                    $options = array("include_player_ids" => [$device->player_id],
                        "data" => array('type' => 4, 'ads_id' => 0, 'push_id' => 1, 'post_id' => ''));

                    \Yii::$app->onesignal->notifications()->create($message, $options);
                }
                return $this->redirect(['view', 'id' => (string)$model->id]);
            } else {
                return $this->renderAjax('_form', [
                    'model' => $model
                ]);

            }
        } elseif (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                'model' => $model
            ]);
        } else {
            return $this->render(['update', 'model' => $model]);
        }
    }

    /**
     * Updates an existing Request model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->id]);
//        } else {
//            return $this->render('update', [
//                'model' => $model,
//            ]);
//        }
        $model->status = 1;
        $model->save();
//        var_dump($model);die;
        $history = new History();

        $history->user_id = $model->user_id;
        $history->amount = $model->amount;
        $history->description = $model->description;
        $history->type = 2; // Nap tien
        $history->status = 1;
        $history->save();
        return $this->redirect(['index', 'model' => $model]);

    }

    /**
     * Deletes an existing Request model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Request model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Request the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Request::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
