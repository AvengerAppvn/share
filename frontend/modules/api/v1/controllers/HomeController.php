<?php

namespace frontend\modules\api\v1\controllers;

use common\models\AdsCategory;
use common\models\Advertise;
use common\models\CategoryAds;
use common\models\User;
use frontend\modules\api\v1\resources\User as UserResource;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\rest\ActiveController;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use Yii;

/**
 * @author Eugene Terentev <eugene@terentev.net>
 */
class HomeController extends ActiveController
{
    /**
     * @var string
     */
    public $modelClass = 'frontend\modules\api\v1\resources\Category';

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
                'guest' => ['get'],
                'category' => ['get'],
                'category-guest' => ['get'],
                'view' => ['get'],
                'create' => ['post'],
                'update' => ['put'],
                'delete' => ['delete'],
                'login' => ['post'],
                'me' => ['get', 'post'],
                'profile' => ['get', 'post'],
                'emotion' => ['get'],
                'addresses' => ['get'],
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
        $behaviors['authenticator']['except'] = ['options', 'guest','category-guest'];


        // setup access
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['index',], //only be applied to
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['index'],
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


        $categoriesResult = [];

        $strengths = [];
        $argStrengths = [];
        $user = User::findIdentity(\Yii::$app->user->getId());
        if($user && $user->userProfile->strengths){
            $argStrengths = json_decode($user->userProfile->strengths);
            $adsCategories = AdsCategory::find()->where(['id'=>$argStrengths])->all();
            foreach ($adsCategories as $adsCategory){
                $categoriesResult[] = array(
                    'id' => $adsCategory->id,
                    'name' => $adsCategory->name,
                    'thumbnail' => $adsCategory->thumbnail,
                    'new' => $adsCategory->new? : 0,

                );
            }
        }
        if($categoriesResult){
            $categories = AdsCategory::find()->where(['not in','id',$argStrengths])->andWhere('id > 0')->limit($page_size)->offset($index)->all();
        }else{
            $categories = AdsCategory::find()->andWhere('id > 0')->limit($page_size)->offset($index)->all();
        }

        foreach ($categories as $category) {

            $categoriesResult[] = array(
                'id' => $category->id,
                'name' => $category->name,
                'thumbnail' => $category->thumbnail,
                'new' => $category->new? : 0,

            );
        }
        return $categoriesResult;
    }

    public function actionGuest()
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

        $categoriesResult = [];
        $categories = AdsCategory::find()->andWhere('id > 0')->limit($page_size)->offset($index)->active()->all();
        foreach ($categories as $category) {

            $categoriesResult[] = array(
                'id' => $category->id,
                'name' => $category->name,
                'thumbnail' => $category->thumbnail,
                'new' => $category->new? : 0,

            );
        }
        return $categoriesResult;
    }

    public function actionCategoryGuest()
    {
        return $this->actionCategory();
    }

    public function actionCategory()
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

        // cat_id
        $cat_id = Yii::$app->request->get('cat_id');
        if(!$cat_id){
            $response->setStatusCode(422);
            return array(
                'name'=> 'Thiếu tham số',
                'message'=> array('cat_id'=> 'Thiếu tham số cat_id'),
                'code'=> 0,
                'status'=> 422,
            );
        }

        $response->setStatusCode(200);
        $advertises = CategoryAds::find()->where(['cat_id'=>[$cat_id]])->limit($page_size)->offset($index)->orderBy('id desc')->all();
        //$advertises = Advertise::find()->where(['cat_id'=>[0,$cat_id]])->limit($page_size)->offset($index)->orderBy('id desc')->active()->all();
        $advertisesResult = [];

        foreach ($advertises as $advertise) {
            if($advertise->advertise) {
                $user = User::findOne($advertise->advertise->created_by);
                $customer_avatar = null;
                $customer_name = null;
                if ($user) {
                    $customer_avatar = $user->userProfile->avatar;
                    $customer_name = $user->userProfile->fullname;
                }
                $advertisesResult[] = array(
                    'id' => $advertise->advertise->id,
                    'title' => $advertise->advertise->title,
                    'description' => $advertise->advertise->description,
                    'thumbnail' => $advertise->advertise->thumb,
                    'created_at' => date('Y-m-d H:i:s', $advertise->advertise->created_at),
                    'customer_avatar' => $customer_avatar ?: '',
                    'customer_name' => $customer_name ?: '',
                );
            }
        }
        return $advertisesResult;
    }

    public function actionAds()
    {
        $response = \Yii::$app->getResponse();
        // ads_id
        $ads_id = Yii::$app->request->get('ads_id');
        if(!$ads_id){
            $response->setStatusCode(422);
            return array(
                'name'=> 'Thiếu tham số',
                'message'=> array('ads_id'=> 'Thiếu tham số ads_id'),
                'code'=> 0,
                'status'=> 422,
            );
        }


        $advertise = Advertise::findOne($ads_id);
        if(!$advertise){
            $response->setStatusCode(404);
            return array(
                'name'=> 'Không có dữ liệu',
                'message'=> array('ads_id'=> 'Không tìm dược dữ liệu với id='.$ads_id),
                'code'=> 0,
                'status'=> 404,
            );
        }

        $response->setStatusCode(200);
        $user = User::findOne($advertise->created_by);
        $customer_avatar = null;
        $customer_name = null;
        if($user){
            $customer_avatar = $user->userProfile->avatar;
            $customer_name = $user->userProfile->fullname;
        }
        return array(
            'id' => $advertise->id,
            'title' => $advertise->title,
            'description' => $advertise->description,
            'content' => $advertise->content,
            'thumbnail' => $advertise->thumb,
            //'images' => $advertise->advertiseImages,
            'images' => [$advertise->thumb],
            'created_at' => date('Y-m-d H:i:s',$advertise->created_at),
            'share' => $advertise->share ?: 0,
            'customer_avatar' => $customer_avatar?:'',
            'customer_name' => $customer_name?:'',
        );
    }
}
