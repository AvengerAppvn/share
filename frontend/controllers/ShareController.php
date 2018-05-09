<?php

namespace frontend\controllers;

use common\models\Advertise;
use common\models\ArticleAttachment;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 */
class ShareController extends Controller
{
    /**
     * @param $slug
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = Advertise::find()->where(['id'=>$id,'status'=>1])->andWhere(['>','share',0])->one();
        if (!$model) {
            throw new NotFoundHttpException;
        }

        return $this->render('view', ['model'=>$model]);
    }

    /**
     * @param $id
     * @return $this
     * @throws NotFoundHttpException
     * @throws \yii\web\HttpException
     */
    public function actionAttachmentDownload($id)
    {
        $model = ArticleAttachment::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException;
        }

        return Yii::$app->response->sendStreamAsFile(
            Yii::$app->fileStorage->getFilesystem()->readStream($model->path),
            $model->name
        );
    }
}
