<?php
namespace frontend\modules\api\v1\controllers;

use Yii;
use frontend\modules\api\v1\resources\Article;
use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;

/**
 * Class ArticleController
 * @author Eugene Terentev <eugene@terentev.net>
 */
class ArticleController extends ActiveController
{
    /**
     * @var string
     */
    public $modelClass = 'frontend\modules\api\v1\resources\Article';
    /**
     * @var array
     */
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items'
    ];

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => 'yii\rest\IndexAction',
                'modelClass' => $this->modelClass,
                'prepareDataProvider' => [$this, 'prepareDataProvider']
            ],
            'view' => [
                'class' => 'yii\rest\ViewAction',
                'modelClass' => $this->modelClass,
                'findModel' => [$this, 'findModel']
            ],
            'options' => [
                'class' => 'yii\rest\OptionsAction'
            ]
        ];
    }

    /**
     * @return ActiveDataProvider
     */
    public function prepareDataProvider()
    {
        return new ActiveDataProvider(array(
            'query' => Article::find()->published()
        ));
    }

    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     * @throws HttpException
     */
    public function findModel($id)
    {
        $model = Article::find()
            ->published()
            ->andWhere(['id' => (int) $id])
            ->one();
        if (!$model) {
            throw new HttpException(404);
        }
        return $model;
    }

    /**
     * Rest Description: Your endpoint description.l
     * Rest Fields: ['field1', 'field2'].
     * Rest Filters: ['filter1', 'filter2'].
     * Rest Expand: ['expandRelation1', 'expandRelation2'].
     */
    public function actionTest()
    {
        //$deviceType = 1;
        //$options = array();
        //\Yii::$app->onesignal->players()->add($deviceType, $options);
        $message = array('vn'=>'Test notification OneSignal');
        $options = array(  "included_segments"=> ["Active Users"],
                            "data"=> array("ads_id"=> 1),);
        \Yii::$app->onesignal->notifications()->create($message, $options);
    }

    public function actionPush()
    {
        //$deviceType = 1;
        //$options = array();
        //\Yii::$app->onesignal->players()->add($deviceType, $options);
        \Yii::$app->onesignal->players()->view();
        //$message = array('vn'=>'Test notification OneSignal');
        //$options = array(  "included_segments"=> ["Active Users"],
        //    "data"=> array("ads_id"=> 1),);
        //\Yii::$app->onesignal->notifications()->create($message, $options);
    }
}
