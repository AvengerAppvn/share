<?php

namespace backend\controllers;

use backend\models\search\UserSearch;
use backend\models\UserForm;
use common\models\User;
use common\models\UserProfile;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionRequest()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->searchRequest(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionVerified()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->searchVerified(Yii::$app->request->queryParams);

        return $this->render('verified', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionNew()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->searchNew(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionConfirm($id)
    {
        $model = UserProfile::find()->where(['user_id' => $id])->one();
        if (!$model) {
            $model = new UserProfile();
            $model->user_id = $id;
            $model->save();
        }

        if (Yii::$app->request->post()) {
            $profie = Yii::$app->request->post('UserProfile');
            if ($profie && $profie['cmt']) {
                $model->cmt = $profie['cmt'];
                if ($model->save()) {
                    $user = $this->findModel($id);
                    $user->status_confirmed = 1;
                    $user->is_confirmed = 1;
                    $user->save();
                }
            }
        }

        return $this->render('confirm', [
            'model' => $model,
        ]);

    }

    public function actionConfirmed($id, $cmt)
    {
        $model = UserProfile::find()->where(['user_id' => $id])->one();
        $user = $this->findModel($id);
        if ($cmt !== null) {
            $model->cmt = $cmt;
            if ($model->save()) {
                $user->status_confirmed = 1;
                $user->is_confirmed = 1;
                $user->save();
                return "Xác thực tài khoản thành công";
            } else {
                return "Xác thực tài khoản không thành công, bạn vui lòng kiểm tra lại.";
            }
        }
        return "Xác thực tài khoản không thành công";
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UserForm();
        $model->setScenario('create');
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
            'roles' => ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'name')
        ]);
    }

    /**
     * Updates an existing User model.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = new UserForm();
        $model->setModel($this->findModel($id));
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
            'roles' => ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'name')
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        Yii::$app->authManager->revokeAll($id);
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
