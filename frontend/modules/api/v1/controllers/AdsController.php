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
use frontend\models\AdsCreateForm;
use frontend\models\AdsUpdateForm;
use frontend\modules\api\v1\resources\RequireCustomer;
use frontend\modules\api\v1\resources\Time;
use Intervention\Image\ImageManagerStatic as Image;
use trntv\filekit\Storage;
use Yii;
use yii\base\ErrorException;
use yii\di\Instance;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\Url;
use yii\rest\ActiveController;
use yii\web\HttpException;

use tpmanc\imagick\Imagick;
/**
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
        $behaviors['authenticator']['except'] = ['options', 'price-basic', 'price', 'view-guest', 'requires', 'time'];


        // setup access
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['view', 'location', 'age', 'share', 'shared', 'change-avatar'], //only be applied to
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['view', 'location', 'age', 'share', 'shared', 'change-avatar'],
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

	/**
	 * #9 api/v1/ads/create
	 * @return array
	 */
    public function actionCreate()
    {
        $user = User::findIdentity(\Yii::$app->user->getId());
        $model = new AdsCreateForm();
        $response = \Yii::$app->getResponse();

        $model->load(\Yii::$app->getRequest()->getBodyParams(), '');
        $model->user_id = $user->id;
	    $wallet = Wallet::findOrCreate($user->id);
        if (intval($wallet->amount) < $model->budget) {
            // Validation error
            $response->setStatusCode(402);
            return 'Tài khoản không đủ tiền';
        }

        if ($model->validate()) {
	        $result = $model->save();
            if (2 == $result) {
                $response->setStatusCode(402);
                return 'Giá đề suất nhỏ hơn giá quy định';
            }

	        if (3 == $result) {
		        $response->setStatusCode(402);
		        return 'Ngân sách không đủ để quảng cáo';
	        }

	        if (4 == $result) {
		        $response->setStatusCode(402);
		        return 'Hết tiền, vui lòng nạp thêm tiền';
	        }

	        $ads = $model->_ads;
            if (1 == $result) {
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
            } else {
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

	public function actionUpdate()
	{
		$user = User::findIdentity(\Yii::$app->user->getId());
		$model = new AdsUpdateForm();
		$response = \Yii::$app->getResponse();

		$model->load(\Yii::$app->getRequest()->getBodyParams(), '');
		$model->user_id = $user->id;
		$wallet = Wallet::findOrCreate($user->id);
		if (intval($wallet->amount) < $model->budget) {
			// Validation error
			$response->setStatusCode(402);
			return 'Tài khoản không đủ tiền';
		}

		if ($model->validate()) {
			$result = $model->update();
			if (2 == $result) {
				$response->setStatusCode(402);
				return 'Giá đề suất nhỏ hơn giá quy định';
			}

			if (3 == $result) {
				$response->setStatusCode(402);
				return 'Ngân sách không đủ để cập nhật quảng cáo';
			}

			if (4 == $result) {
				$response->setStatusCode(402);
				return 'Hết tiền, vui lòng nạp thêm tiền';
			}

			$ads = $model->_ads;
			if (1 == $result) {
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
			} else {
				$response->setStatusCode(402);
				return 'Không cập nhật được quảng cáo';
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

        if ($advertise->price_on_share) {
            $price_on_share = $advertise->price_on_share;
        } else {
            // Tính giá share
            $price_on_share = $this->getPriceUnit();
            $advertise->price_on_share = $this->getPriceUnit();
            $advertise->save();
        }

        if ($advertise->budget_remain) {
            $budget_remain = $advertise->budget_remain;
        } else {
            //
            $budget_remain = $advertise->share * $price_on_share;
            $advertise->budget_remain = $advertise->share * $price_on_share;
            $advertise->save();
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
            'user_id' => $advertise->created_by,
            'budget_remain' => $budget_remain,
            'price_on_share' => $price_on_share,
            'ads_type' => $advertise->ads_type,
            'time_type' => $advertise->time_type,
            'criteria' => json_decode($advertise->criteria),
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
            'price_basic' => (int)\Yii::$app->keyStorage->get('config.price-basic', 5000),
            'service' => (int)\Yii::$app->keyStorage->get('config.service', 20),
            'option' => (int)\Yii::$app->keyStorage->get('config.option', 10),
            'price_avatar' => (int)\Yii::$app->keyStorage->get('config.price-avatar', 100000),
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
        return array(
            'id' => $advertise->id,
            'return' => 2000,
        );
    }


    public function actionRequires()
    {
        //$response = \Yii::$app->getResponse();
        //$response->setStatusCode(200);
        return RequireCustomer::find()->all();
    }

    public function actionTime()
    {
        //$response = \Yii::$app->getResponse();
        //$response->setStatusCode(200);
        return Time::find()->all();
    }


    public function actionChangeAvatar()
    {
        $user = User::findIdentity(\Yii::$app->user->getId());
        $response = \Yii::$app->getResponse();
        // ads_id
        $ads_id = Yii::$app->request->post('ads_id');
        $fbid = Yii::$app->request->post('fbid');
        if (!$ads_id) {
            $response->setStatusCode(422);
            return 'Thiếu tham số ads_id';
        }

        if (!$fbid) {
            $response->setStatusCode(422);
            return 'Thiếu tham số fbid';
        }

        $advertise = Advertise::findOne($ads_id);
        if (!$advertise) {
            $response->setStatusCode(404);
            return 'Không có dữ liệu với id=' . $ads_id;
        }

        // TODO
        // Get avatar facebook
	    $avatar_url = "http://graph.facebook.com/$fbid/picture?type=large";

	    $facebook_picture = file_get_contents($avatar_url);
        // Compare with image
        $compare = $this->compare($avatar_url,$advertise->getThumb());;
        // Chọn cái nào để đăng avatar facebook

        if ($compare > 95) {
            $status = true;
            $description = 'Thành công';
        } else {
            $status = false;
            $description = 'Không hợp lệ';
        }
        return array(
            'status' => $status,
            'point' => (string)$compare,
            'description' => $description
        );
    }


    private function getPriceUnit()
    {
        $percent = \Yii::$app->keyStorage->get('config.service', 20);
        $price_base = (int)\Yii::$app->keyStorage->get('config.price-basic', 5000);
        $option = (int)\Yii::$app->keyStorage->get('config.option', 10);
        $price_unit = $price_base;
//        if ($this->location && $this->location > 0) {
//            $price_unit += $price_base * $option/100;
//        }
//        if ($this->age && $this->age > 0) {
//            $price_unit += $price_base * $option/100;
//        }
//
//        if ($this->category && $this->category > 0) {
//            $price_unit += $price_base * $option/100;
//        }
        $price_unit += $price_base * $percent/100;
        return $price_unit;
    }

	private function mimeType($i)
	{
		/*returns array with mime type and if its jpg or png. Returns false if it isn't jpg or png*/
		$mime = getimagesize($i);
		$return = array($mime[0],$mime[1]);

		switch ($mime['mime'])
		{
			case 'image/jpeg':
				$return[] = 'jpg';
				return $return;
			case 'image/png':
				$return[] = 'png';
				return $return;
			default:
				return false;
		}
	}

	private function createImage($i)
	{
		/*retuns image resource or false if its not jpg or png*/
		$mime = $this->mimeType($i);

		if($mime[2] == 'jpg')
		{
			return imagecreatefromjpeg ($i);
		}
		else if ($mime[2] == 'png')
		{
			return imagecreatefrompng ($i);
		}
		else
		{
			return false;
		}
	}

	private function resizeImage($i,$source)
	{
		/*resizes the image to a 8x8 squere and returns as image resource*/
		$mime = $this->mimeType($source);

		$t = imagecreatetruecolor(8, 8);

		$source = $this->createImage($source);

		imagecopyresized($t, $source, 0, 0, 0, 0, 8, 8, $mime[0], $mime[1]);

		return $t;
	}

	private function colorMeanValue($i)
	{
		/*returns the mean value of the colors and the list of all pixel's colors*/
		$colorList = array();
		$colorSum = 0;
		for($a = 0;$a<8;$a++)
		{
			for($b = 0;$b<8;$b++)
			{

				$rgb = imagecolorat($i, $a, $b);
				$colorList[] = $rgb & 0xFF;
				$colorSum += $rgb & 0xFF;

			}
		}

		return array($colorSum/64,$colorList);
	}

	private function bits($colorMean)
	{
		/*returns an array with 1 and zeros. If a color is bigger than the mean value of colors it is 1*/
		$bits = array();

		foreach($colorMean[1] as $color){$bits[]= ($color>=$colorMean[0])?1:0;}
		return $bits;
	}

	private function compare($imageOrigin,$imageNew)
	{

		$a = $imageOrigin;
		$b = $imageNew;
		/*main function. returns the hammering distance of two images' bit value*/
		$i1 = $this->createImage($a);
		$i2 = $this->createImage($b);

		if(!$i1 || !$i2){return false;}

		$i1 = $this->resizeImage($i1,$a);
		$i2 = $this->resizeImage($i2,$b);

		imagefilter($i1, IMG_FILTER_GRAYSCALE);
		imagefilter($i2, IMG_FILTER_GRAYSCALE);

		$colorMean1 = $this->colorMeanValue($i1);
		$colorMean2 = $this->colorMeanValue($i2);

		$bits1 = $this->bits($colorMean1);
		$bits2 = $this->bits($colorMean2);

		$hammeringDistance = 0;

		for($a = 0;$a<64;$a++)
		{
			if($bits1[$a] != $bits2[$a])
			{
				$hammeringDistance++;
			}
		}
		return (64 - $hammeringDistance)* 100 / 64;

	}
}
