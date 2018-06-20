<?php

namespace frontend\modules\api\v1\controllers;

use common\models\AdsCategory;
use common\models\AdsShare;
use common\models\Advertise;
use common\models\CriteriaAge;
use common\models\CriteriaProvince;
use common\models\Notification;
use common\models\Transaction;
use common\models\User;
use common\models\Wallet;
use frontend\models\AdsDepositForm;
use frontend\models\AdsForm;
use frontend\modules\api\v1\resources\Campaign;
use frontend\modules\api\v1\resources\CampaignUpdate;
use Intervention\Image\ImageManagerStatic as Image;
use trntv\filekit\Storage;
use Yii;
use yii\base\ErrorException;
use yii\di\Instance;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;
use yii\web\HttpException;

/**
 * @author Eugene Terentev <eugene@terentev.net>
 */
class CampaignController extends ActiveController
{
    /**
     * @var string
     */
    public $modelClass = 'frontend\modules\api\v1\resources\Campaign';

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
        $behaviors['authenticator']['except'] = ['options', 'price-basic', 'price', 'view-guest'];


        // setup access
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['view', 'report', 'deposit', 'pause', 'stop','me'], //only be applied to
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['view', 'report', 'deposit', 'pause', 'stop','me'],
                    'roles' => ['@']
                ]
            ],
        ];

        return $behaviors;
    }

    public function actionPause()
    {
        $user = User::findIdentity(\Yii::$app->user->getId());
        $response = \Yii::$app->getResponse();
        // ads_id
        $ads_id = Yii::$app->request->post('ads_id');
        if (!$ads_id) {
            $response->setStatusCode(422);
            return 'Thiếu tham số ads_id';
        }

        $ads = Advertise::findOne(['id' => $ads_id, 'created_by' => $user->id]);
        if (!$ads) {
            $response->setStatusCode(422);
            return 'Không tồn tại quảng cáo';
        }

        if (Advertise::STATUS_ACTIVE == $ads->status) {
            $ads->status = Advertise::STATUS_PAUSE;
            $ads->save();
            // TODO notification
            $response->setStatusCode(200);
            return array(
                'ads_id'=>$ads->id,
                'message'=>'Bạn đã dừng chiến dịch thành công',
                'status'=>$ads->status,
                'status_description'=>'Chiến dịch tạm dừng',
            );
           // return 'Bạn đã dừng chiến dịch thành công';
        }else{
            $response->setStatusCode(422);
            return 'Không thể dừng quảng cáo này';
        }

    }

    public function actionStop()
    {
        $user = User::findIdentity(\Yii::$app->user->getId());
        $response = \Yii::$app->getResponse();
        // ads_id
        $ads_id = Yii::$app->request->post('ads_id');
        if (!$ads_id) {
            $response->setStatusCode(422);
            return 'Thiếu tham số ads_id';
        }

        $ads = Advertise::findOne(['id' => $ads_id, 'created_by' => $user->id]);
        if (!$ads) {
            $response->setStatusCode(422);
            return 'Không tồn tại quảng cáo';
        }

        if (Advertise::STATUS_PAUSE == $ads->status) {
            $ads->status = Advertise::STATUS_STOP;
            $ads->save();
            // TODO notification
            $response->setStatusCode(200);
            return array(
                'ads_id'=>$ads->id,
                'message'=>'Bạn đã hủy chiến dịch thành công',
                'status'=>$ads->status,
                'status_description'=>'Chiến dịch đã hủy',
            );
        }else{
            $response->setStatusCode(422);
            return 'Không thể hủy chiến dịch này';
        }
    }
    public function actionDeposit()
    {
        $user = User::findIdentity(\Yii::$app->user->getId());
        $response = \Yii::$app->getResponse();
        // ads_id
        $ads_id = Yii::$app->request->post('ads_id');
        if (!$ads_id) {
            $response->setStatusCode(422);
            return 'Thiếu tham số ads_id';
        }

        $ads = Advertise::findOne(['id' => $ads_id, 'created_by' => $user->id]);
        if (!$ads) {
            $response->setStatusCode(422);
            return 'Không tồn tại quảng cáo';
        }

        $budget = Yii::$app->request->post('budget');
        if (!$budget) {
            $response->setStatusCode(422);
            return 'Thiếu tham số budget';
        }
        $model = new AdsDepositForm();
        $response = \Yii::$app->getResponse();

        $model->load(\Yii::$app->getRequest()->getBodyParams(), '');
        $model->user_id = $user->id;
        $wallet = Wallet::find()->where(['user_id' => $user->id])->one();

        if (!$wallet || intval($wallet->amount) < $model->budget) {
            // Validation error
            $response->setStatusCode(402);
            return 'Tài khoản không đủ tiền';
        }

        if ($model->validate()) {
            if ($model->calculateShare($ads) <= 0) {
                $response->setStatusCode(402);
                return 'Ngân sách không đủ để quảng cáo';
            }

            if (($ads = $model->save())) {
                $response->setStatusCode(200);
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
            }else{
                $response->setStatusCode(402);
                return 'Không nạp được tiền cho quảng cáo';
            }
        } else {
            // Validation error
            $message = '';
            foreach ($model->errors as $error) {
                $message .= $error[0];
            }
            throw new HttpException(422, $message);
        }
    }

    public function actionMe()
    {
        $user = User::findIdentity(\Yii::$app->user->getId()); //$user->getId()
        // tab
        $tab = Yii::$app->request->get('tab');
        switch ($tab){
            case 1: $query = Campaign::find(['created_by'=>\Yii::$app->user->getId()])->active();break;
            case 2: $query = Campaign::find(['created_by'=>\Yii::$app->user->getId()])->pauseAndPending();break;
            case 3: $query = Campaign::find(['created_by'=>\Yii::$app->user->getId()])->finish();break;
            case 4: $query = Campaign::find(['created_by'=>\Yii::$app->user->getId()])->stop();break;
            default:$query = Campaign::find(['created_by'=>\Yii::$app->user->getId()])->active();break;
        }
        return new ActiveDataProvider(array(
            'query' => $query
        ));

    }
    public function actionReport()
    {

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

        if ($model->validate()) {
            if ($model->calculateShare() <= 0) {
                $response->setStatusCode(402);
                return 'Ngân sách không đủ để quảng cáo';
            }

            if (($ads = $model->save())) {
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
            }else{
                $response->setStatusCode(402);
                return 'Không tạo được quảng cáo';
            }
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

                $basic = \Yii::$app->keyStorage->get('config.price-basic', 5000);
                $wallet = Wallet::find()->where(['user_id' => $user->id])->one();
                if ($wallet) {
                    $wallet->amount = $wallet->amount + $basic;
                    $wallet->save();
                } else {
                    $wallet = new Wallet();
                    $wallet->user_id = $user->id;
                    $wallet->amount = $basic;
                    $wallet->status = 1;
                    $wallet->save();
                }

                $transaction = new Transaction();
                $transaction->description = "Share " . $advertise->title;
                $transaction->amount = $basic;
                $transaction->user_id = $user->id;
                $transaction->type = Transaction::TYPE_DEPOSIT; // Thu
                $transaction->save();

                $notification = new Notification();
                $notification->title = "Bạn nhận được " . $basic . "k từ share " . $advertise->title;
                $notification->description = "Chia sẻ thành công và nhận " . $basic . "k từ share " . $advertise->title;
                $notification->user_id = $user->id;
                $notification->ads_id = $advertise->id;
                $notification->save();

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
        if (!$advertise || Advertise::STATUS_ACTIVE != $advertise->status) {
            $response->setStatusCode(404);
            return 'Không có dữ liệu với id=' . $ads_id;
        }

        $response->setStatusCode(200);
        $user = User::findIdentity(\Yii::$app->user->getId());

        $owner = User::findOne($advertise->created_by);
        $customer_avatar = null;
        $customer_name = null;
        $customer_phone = null;
        $customer_email = null;
        if ($user) {
            $customer_avatar = $owner->userProfile->avatar;
            $customer_name = $owner->userProfile->fullname;
            $customer_email = $owner->email;
            $customer_phone = $owner->phone;
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

        if ($advertise->thumb) {
            $thumbnail_generate = '';
        } else {
            $fileStorage = Instance::ensure('fileStorage', Storage::className());
            if (is_file(\Yii::getAlias('@storage') . '/web/source/shares/bg_color_' . $advertise->id . '.png')) {
                $thumbnail_generate = $fileStorage->baseUrl . '/shares/bg_color_' . $advertise->id . '.png';
            } else {
                // configure with favored image driver (gd by default)
                Image::configure(array('driver' => 'imagick'));
                $image = Image::make('img/bg_color.png')->text($advertise->description, 320, 320, function ($font) {
                    $font->file('font/arial.ttf');
                    $font->size(30);
                    $font->color('#fdf6e3');
                    $font->align('center');
                    $font->valign('bottom');
                    //$font->angle(45);
                });
                //
                $image->save(\Yii::getAlias('@storage') . '/web/source/shares/bg_color_' . $advertise->id . '.png');

                $thumbnail_generate = $fileStorage->baseUrl . '/shares/bg_color_' . $advertise->id . '.png';
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
            'customer_phone' => $customer_phone ?: '',
            'customer_email' => $customer_email ?: '',
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

    public function actionPrice()
    {
        $response = \Yii::$app->getResponse();
        $response->setStatusCode(200);
        return array(
            'price_basic' => \Yii::$app->keyStorage->get('config.price-basic', 5000),
            'service' => \Yii::$app->keyStorage->get('config.service', 20),
            'option' => \Yii::$app->keyStorage->get('config.option', 10),
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

    public function actionCancel()
    {
        $response = \Yii::$app->getResponse();
        // ads_id
        $ads_id = Yii::$app->request->post('ads_id');
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

        $shares = AdsShare::find()->where(['ads_id' => $ads_id])->all();
        $sharesResult = [];
        $advertise->status = Advertise::STATUS_CANCEL;
        $advertise->save();
        return  array(
            'id' => $advertise->id,
            'return' => 2000,
        );
    }
}
