<?php

namespace frontend\modules\api\v1\controllers;

use common\models\AdsCategory;
use common\models\AdsShare;
use common\models\Advertise;
use common\models\CriteriaAge;
use common\models\CriteriaProvince;
use common\models\User;
use common\models\Wallet;
use frontend\models\AdsForm;
use Intervention\Image\ImageManagerStatic as Image;
use Yii;
use yii\base\ErrorException;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\Url;
use yii\rest\ActiveController;
use yii\web\HttpException;
use trntv\filekit\Storage;
use yii\di\Instance;
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
                'view-guest' => ['get'],
                'create' => ['post'],
                'update' => ['put'],
                'delete' => ['delete'],
                'login' => ['post'],
                'me' => ['get', 'post'],
                'profile' => ['get', 'post'],
                'emotion' => ['get'],
                'share' => ['post'],
                'shared' => ['get'],
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
        $behaviors['authenticator']['except'] = ['options', 'price-basic', 'view-guest'];


        // setup access
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['view', 'location', 'age', 'share', 'shared'], //only be applied to
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['view', 'location', 'age', 'share', 'shared'],
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

        $categories = AdsCategory::find()->limit($page_size)->offset($index)
            ->orderBy('id desc')
            ->all();
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
        $user = User::findIdentity(\Yii::$app->user->getId());
        $model = new AdsForm();
        $response = \Yii::$app->getResponse();

        $model->load(\Yii::$app->getRequest()->getBodyParams(), '');
        $model->user_id = $user->id;
        $wallet = Wallet::find()->where(['user_id' => $user->id])->one();
        if (!$wallet || intval($wallet->amount) < $model->budget) {
            // Validation error
            $response->setStatusCode(402);
            return 'Tài khoản không đủ tiền';
        }
        if ($model->validate() && ($ads = $model->save())) {
            $response->setStatusCode(200);
            $response->getHeaders()->set('Location', Url::toRoute([$ads->id], true));
            return array(
                'id' => $ads->id,
                'title' => $ads->title,
                'require' => $ads->content,
                'message' => $ads->description,
                'budget' => $ads->budget,
                'cat_id' => $ads->cat_id,
                'age_min' => $ads->age_min,
                'age_max' => $ads->age_max,
                'created_at' => date('Y-m-d H:i:s', $ads->created_at),
                'thumbnail' => $ads->thumb,
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


    public function actionShare()
    {
        $user = User::findIdentity(\Yii::$app->user->getId());
        $response = \Yii::$app->getResponse();
        // ads_id
        $ads_id = Yii::$app->request->post('ads_id');
        $post_id = Yii::$app->request->post('post_id');
        if (!$ads_id) {
            $response->setStatusCode(422);
            return 'Thiếu tham số ads_id';
        }

        if (!$post_id) {
            $response->setStatusCode(422);
            return 'Thiếu tham số post_id';
        }

        if (AdsShare::find()->where(['ads_id' => $ads_id, 'user_id' => $user->id])->exists()) {
            $response->setStatusCode(422);
            return 'Đã share quảng cáo này';
        }
        $advertise = Advertise::findOne($ads_id);
        if (!$advertise) {
            $response->setStatusCode(404);
            return 'Không có dữ liệu với id=' . $ads_id;
        }
        if ($advertise->share <= 0) {
            $response->setStatusCode(422);
            return 'Đã hết lượt share';
        } else {
            $model = new AdsShare();
            $model->ads_id = $ads_id;
            $model->user_id = $user->id;
            $model->post_id = $post_id;
            if ($model->save()) {
                if ($advertise->share > 0) {
                    $advertise->share -= 1;
                    $advertise->save(false);
                }

                $response->setStatusCode(200);
                $response->getHeaders()->set('Location', Url::toRoute([$model->id], true));
                return $model;
            } else {
                // Validation error
                $message = '';
                foreach ($model->errors as $error) {
                    $message .= $error[0];
                }
                throw new HttpException(422, $message);
            }
        }
    }

    public function actionView()
    {
        $response = \Yii::$app->getResponse();
        // ads_id
        $ads_id = Yii::$app->request->get('ads_id');
        if (!$ads_id) {
            $response->setStatusCode(422);
            return 'Thiếu tham số ads_id';
        }

        $advertise = Advertise::findOne($ads_id);
        if (!$advertise) {
            $response->setStatusCode(404);
            return 'Không có dữ liệu với id=' . $ads_id;
        }

        $response->setStatusCode(200);
        $user = User::findIdentity(\Yii::$app->user->getId());

        $owner = User::findOne($advertise->created_by);
        $customer_avatar = null;
        $customer_name = null;
        if ($user) {
            $customer_avatar = $owner->userProfile->avatar;
            $customer_name = $owner->userProfile->fullname;
        }

        $images = [];
        foreach ($advertise->advertiseImages as $adsImage) {
            try {
                list($width, $height) = getimagesize(\Yii::getAlias('@storage') . '/web/source/' . $adsImage->image_path);
            } catch (ErrorException $e) {
                $width = $height = 0;
            }
            $images[] = array(
                'image' => $adsImage->image,
                'width' => $width,
                'height' => $height
            );
        }
        try {
            list($width, $height) = getimagesize(\Yii::getAlias('@storage') . '/web/source/' . $advertise->thumbnail_path);
        } catch (ErrorException $e) {
            $width = $height = 0;
        }

        if($advertise->thumb){
            $thumbnail_generate = '';
        }else{
            $fileStorage = Instance::ensure('fileStorage', Storage::className());
            if (is_file(\Yii::getAlias('@storage') . '/web/source/shares/bg_color_'.$advertise->id.'.png')) {
                $thumbnail_generate = $fileStorage->baseUrl.'/shares/bg_color_'.$advertise->id.'.png';
            }else{
                // configure with favored image driver (gd by default)
                Image::configure(array('driver' => 'imagick'));
                $image = Image::make('img/bg_color.png')->text($advertise->description,100,200,function($font) {
                    $font->file('font/arial.ttf');
                    $font->size(24);
                    $font->color('#fdf6e3');
                    $font->align('center');
                    $font->valign('top');
                    //$font->angle(45);
                });
                //
                $image->save(\Yii::getAlias('@storage') . '/web/source/shares/bg_color_'.$advertise->id.'.png');

                $thumbnail_generate = $fileStorage->baseUrl.'/shares/bg_color_'.$advertise->id.'.png';
            }

        }
        return array(
            'id' => $advertise->id,
            'title' => $advertise->title,
            'description' => $advertise->description,
            'content' => $advertise->content,
            'thumbnail' => $advertise->thumb,
            'thumbnail_generate' => $thumbnail_generate,
            'thumbnail_width' => $width,
            'thumbnail_height' => $height,
            'images' => $images,
            'created_at' => date('Y-m-d H:i:s', $advertise->created_at),
            'share' => $advertise->share ?: 0,
            'shared_count' => intval(AdsShare::find()->where(['ads_id' => $advertise->id])->count()),
            'customer_avatar' => $customer_avatar ?: '',
            'customer_name' => $customer_name ?: '',
            'is_shared' => AdsShare::find()->where(['ads_id' => $advertise->id, 'user_id' => $user->id])->exists() ? 1 : 0,
        );
    }

    public function actionShared()
    {
        $response = \Yii::$app->getResponse();
        // ads_id
        $ads_id = Yii::$app->request->get('ads_id');
        if (!$ads_id) {
            $response->setStatusCode(422);
            return 'Thiếu tham số ads_id';
        }

        $advertise = Advertise::findOne($ads_id);
        if (!$advertise) {
            $response->setStatusCode(404);
            return 'Không có dữ liệu với id=' . $ads_id;
        }

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

        $shares = AdsShare::find()->where(['ads_id' => $ads_id])->limit($page_size)->offset($index)->all();
        $sharesResult = [];

        foreach ($shares as $share) {

            $sharesResult[] = array(
                'id' => $share->id,
                'name' => $share->user->userProfile->fullname,
                'post_id' => $share->post_id,
            );
        }
        return $sharesResult;
    }

    public function actionViewGuest()
    {
        $response = \Yii::$app->getResponse();
        // ads_id
        $ads_id = Yii::$app->request->get('ads_id');
        if (!$ads_id) {
            $response->setStatusCode(422);
            return 'Thiếu tham số ads_id';
        }

        $advertise = Advertise::findOne($ads_id);
        if (!$advertise) {
            $response->setStatusCode(404);
            return 'Không có dữ liệu với id=' . $ads_id;
        }

        $response->setStatusCode(200);

        $user = User::findOne($advertise->created_by);
        $customer_avatar = null;
        $customer_name = null;
        if ($user) {
            $customer_avatar = $user->userProfile->avatar;
            $customer_name = $user->userProfile->fullname;
        }

        $images = [];
        foreach ($advertise->advertiseImages as $adsImage) {
            $images[] = $adsImage->image;
        }

        return array(
            'id' => $advertise->id,
            'title' => $advertise->title,
            'description' => $advertise->description,
            'content' => $advertise->content,
            'thumbnail' => $advertise->thumb,
            'images' => $images,
            'created_at' => date('Y-m-d H:i:s', $advertise->created_at),
            'share' => $advertise->share ?: 0,
            'shared_count' => intval(AdsShare::find()->where(['ads_id' => $advertise->id])->count()),
            'customer_avatar' => $customer_avatar ?: '',
            'customer_name' => $customer_name ?: '',
            'is_shared' => 0,
        );
    }

    public function actionPriceBasic()
    {
        $response = \Yii::$app->getResponse();
        $response->setStatusCode(200);
        return \Yii::$app->keyStorage->get('config.price-basic', 5000);
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
