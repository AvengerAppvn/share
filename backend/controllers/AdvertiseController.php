<?php

namespace backend\controllers;

use common\models\AdsAdvertiseImage;
use common\models\AdsAdvertiseShare;
use common\models\AdsShare;
use common\models\CriteriaAge;
use common\models\CriteriaProvince;
use common\models\CriteriaSpeciality;
use Yii;
use common\models\Advertise;
use common\models\search\AdvertiseSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AdvertiseController implements the CRUD actions for Advertise model.
 */
class AdvertiseController extends Controller
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
     * Lists all Advertise models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AdvertiseSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Advertise model.
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
     * Creates a new Advertise model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Advertise();
        $image = new AdsAdvertiseImage();
        $share = new AdsShare();
//        $model->id = $model->created_by;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $share->ads_id = $model->id;
            $share->user_id = $model->created_by;
            $share->status = $model->status;
            $share->save();
            return $this->redirect(['view', 'id' => $model->id,]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'image' => $image,
            ]);
        }
    }

    public function actionShare($ads_id)
    {
        $model = AdsShare::find()->where(['ads_id' => $ads_id])->all();
        $count = count($model);

        return $this->render('share', [
            'model' => $model,
            'count' => $count
        ]);
    }

    /**
     * Updates an existing Advertise model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $share = AdsShare::find()->where(['user_id' => $model->created_by, 'ads_id' => $id])->one();

        $image = new AdsAdvertiseImage();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $share->status = $model->status;
            $share->save();

            return $this->redirect(['view', 'id' => $model->id,]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'image' => $image,
            ]);
        }
    }

    /**
     * Deletes an existing Advertise model.
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
     * Finds the Advertise model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Advertise the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Advertise::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findShare($id)
    {
        if (($model = AdsShare::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
