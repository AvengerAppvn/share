<?php

namespace frontend\modules\api\v1\controllers;

use common\models\AdsAdvertiseImage;
use common\models\AdsAdvertiseShare;
use common\models\AdsCategory;
use common\models\AdsShare;
use common\models\Advertise;
use common\models\CriteriaAge;
use common\models\CriteriaProvince;
use common\models\User;
use frontend\models\AdsForm;
use frontend\modules\api\v1\resources\User as UserResource;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\Url;
use yii\rest\ActiveController;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use Yii;
/**
 * @author Eugene Terentev <eugene@terentev.net>
 */
class AdsController extends ActiveController
{
    /**
     * @var string
     */
    public $modelClass = 'frontend\modules\api\v1\resources\Advertise';

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
                'create' => ['post'],
                'update' => ['put'],
                'delete' => ['delete'],
                'login' => ['post'],
                'me' => ['get', 'post'],
                'profile' => ['get', 'post'],
                'emotion' => ['get'],
                'share' => ['post'],
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
            'only' => ['view','location','age','share'], //only be applied to
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['view','location','age','share'],
                    'roles' => ['@']
                ]
            ],
        ];

        return $behaviors;
    }

    public function actionIndex()
    {
        $page_size = Yii::$app->request->get('page_size');
        $page_index = Yii::$app->request->get('page_index');
        if (!$page_size) {
            $page_size = 8;
        }

        if (!$page_index) {
            $page_index = 1;
        }

        $index = $page_size * ($page_index - 1);

        $response = \Yii::$app->getResponse();
        $response->setStatusCode(200);

        $categories = AdsCategory::find()->limit($page_size)->offset($index)->all();
        $categoriesResult = [];

        foreach ($categories as $category) {

            $categoriesResult[] = array(
                'id' => $category->id,
                'name' => $category->name,
                'thumbnail' => $category->thumbnail,
                'new' => 0,

            );
        }
        return $categoriesResult;
    }

    public function actionCreate()
    {
        $model = new AdsForm();
        $model->load(\Yii::$app->getRequest()->getBodyParams(), '');

        if ($model->validate() && ($ads = $model->save())) {
            $response = \Yii::$app->getResponse();
            $response->setStatusCode(200);
            $response->getHeaders()->set('Location', Url::toRoute([$ads->id], true));
            return array(
                'id'=> $ads->id,
                'title'=> $ads->title,
                'require'=> $ads->content,
                'message'=> $ads->description,
                'cat_id'=> 1,
                'created_at'=> date('Y-m-d H:i:s',$ads->created_at),
                'thumbnail'=> $ads->thumb,
            );
        } else {
            // Validation error
            throw new HttpException(422, json_encode($model->errors));
        }
    }


    public function actionShare()
    {
        $user = User::findIdentity(\Yii::$app->user->getId());
        $response = \Yii::$app->getResponse();
        // ads_id
        $ads_id = Yii::$app->request->post('ads_id');
        if(!$ads_id){
            $response->setStatusCode(422);
            return 'Thiếu tham số ads_id';
        }

        if(AdsShare::find()->where(['ads_id'=>$ads_id,'user_id'=>$user->id])->exists()) {
            $response->setStatusCode(422);
            return 'Đã share quảng cáo này';
        }
        $advertise = Advertise::findOne($ads_id);
        if(!$advertise){
            $response->setStatusCode(404);
            return 'Không có dữ liệu với id='.$ads_id;
        }
        if($advertise->share <= 0){
            $response->setStatusCode(422);
            return 'Đã hết lượt share';
        }else{
            $model = new AdsShare();
            $model->ads_id = $ads_id;
            $model->user_id = $user->id;
            if ($model->save()) {
                if($advertise->share > 0){
                    $advertise->share -= 1;
                    $advertise->save(false);
                }

                $response->setStatusCode(200);
                $response->getHeaders()->set('Location', Url::toRoute([$model->id], true));
                return $model;
            } else {
                // Validation error
                throw new HttpException(422, json_encode($model->errors));
            }
        }
    }

    public function actionView()
    {
        $response = \Yii::$app->getResponse();
        // ads_id
        $ads_id = Yii::$app->request->get('ads_id');
        if(!$ads_id){
            $response->setStatusCode(422);
            return 'Thiếu tham số ads_id';
        }

        $advertise = Advertise::findOne($ads_id);
        if(!$advertise){
            $response->setStatusCode(404);
            return 'Không có dữ liệu với id='.$ads_id;
        }

        $response->setStatusCode(200);

        $user = User::findOne($advertise->created_by);
        $customer_avatar = null;
        $customer_name = null;
        if($user){
            $customer_avatar = $user->userProfile->avatar;
            $customer_name = $user->userProfile->fullname;
        }

        $images = [];
        foreach($advertise->advertiseImages as $adsImage){
            $images[] = $adsImage->image;
        }

        return array(
            'id' => $advertise->id,
            'title' => $advertise->title,
            'description' => $advertise->description,
            'content' => $advertise->content,
            'thumbnail' => $advertise->thumb,
            'images' => $images,
            'created_at' => date('Y-m-d H:i:s',$advertise->created_at),
            'share' => $advertise->share ?: 0,
            'customer_avatar' => $customer_avatar?:'',
            'customer_name' => $customer_name?:'',
        );
    }

    public function actionLocation()
    {
        $response = \Yii::$app->getResponse();
        $response->setStatusCode(200);

        $provinces = CriteriaProvince::find()->all();
        $locationsResult = [];

        foreach ($provinces as $province) {

            $locationsResult[] = array(
                'id' => $province->id,
                'name' => $province->name,
            );
        }
        return $locationsResult;
    }

    public function actionAge()
    {
        $response = \Yii::$app->getResponse();
        $response->setStatusCode(200);

        $ages = CriteriaAge::find()->all();
        $agesResult = [];

        foreach ($ages as $age) {

            $agesResult[] = array(
                'id' => $age->id,
                'name' => $age->name,
            );
        }
        return $agesResult;
    }
}
