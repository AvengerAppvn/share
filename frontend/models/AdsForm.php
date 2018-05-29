<?php

namespace frontend\models;

use common\models\AdsAdvertiseImage;
use common\models\AdsCategory;
use common\models\Advertise;
use common\models\CategoryAds;
use common\models\Transaction;
use common\models\Wallet;
use trntv\filekit\Storage;
use Yii;
use yii\base\Model;
use yii\di\Instance;

/**
 * Ads form
 */
class AdsForm extends Model
{
    public $title;
    public $images;
    public $location;
    public $require;
    public $message;
    public $age;
    public $category;
    public $budget;
    public $user_id;
    public $age_min;
    public $age_max;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['title', 'trim'],
            //['title', 'required', 'message' => Yii::t('frontend', 'Missing title')],
            ['budget', 'required', 'message' => Yii::t('frontend', 'Missing budget')],
            ['require', 'required', 'message' => Yii::t('frontend', 'Missing require')],
            [['title', 'require', 'message'], 'string'],
            ['age_max', 'integer', 'max' => 80],
            ['age_min', 'integer', 'min' => 18],
            [['age_max'], 'compare', 'compareAttribute' => 'age_min', 'operator' => '>=', 'skipOnEmpty' => true],
            [['images', 'location', 'age', 'category'], 'safe']
        ];
    }

    /**
     *
     * @return boolean the saved model or null if saving fails
     */
    public function save()
    {
        if ($this->validate()) {
            $model = new Advertise();
            $model->title = $this->title;
            $model->content = $this->require;
            $model->require = $this->require;
            $model->message = $this->message;
            $model->description = $this->message;
            $model->budget = $this->budget;
            if ($this->category) {
                $model->cat_id = $this->category[0];
            } else {
                $model->cat_id = 0;
            }

            $model->user_id = $this->user_id;
            $model->age_min = $this->age_min;
            $model->age_max = $this->age_max;

            $price = $this->getPriceUnit();
            $share = $this->calculateShare();
            $realMoney = $this->getRealMoney($share,$price);
            $wallet = Wallet::find()->where(['user_id' => $this->user_id])->one();
            if ($wallet && $wallet->amount >= $model->budget) {
                $wallet->amount = $wallet->amount - $realMoney;
                $wallet->save();
            } else {
                return false; // Out of money
            }
            // TODO fix share
            $model->share =$share;
            $model->status = Advertise::STATUS_PENDING;

            if ($model->save(false)) {
                $primaryKey = $model->getPrimaryKey();
                $tags = [];
                if ($model->cat_id == 0) {
                    $this->category = AdsCategory::find()->all();
                    foreach ($this->category as $cat) {
                        $catAds = new CategoryAds();
                        $catAds->cat_id = $cat->id;
                        $catAds->ads_id = $primaryKey;
                        $catAds->save();

                        $tags[] = $cat->slug;
                    }
                } else {
                    if ($this->category) {
                        foreach ($this->category as $cat) {
                            $cate = AdsCategory::findOne($cat);
                            if ($cate) {
                                $catAds = new CategoryAds();
                                $catAds->cat_id = $cat;
                                $catAds->ads_id = $primaryKey;
                                $catAds->save();

                                $tags[] = $cate->slug;
                            }
                        }
                    }
                }

                $transaction = new Transaction();
                $transaction->description = "Chi quảng cáo " . $this->title;
                $transaction->user_id = $this->user_id;
                $transaction->amount = $model->budget;
                $transaction->type = Transaction::TYPE_WITHDRAW; // Chi
                $transaction->save();

                if ($this->images) {
                    // requires php5
                    define('UPLOAD_DIR', \Yii::getAlias('@storage') . '/web/source/shares/');
                    $fileStorage = Instance::ensure('fileStorage', Storage::className());

                    foreach ($this->images as $image) {
                        $adsImage = new AdsAdvertiseImage();
                        $adsImage->ads_id = $primaryKey;
                        $img = $image;
                        $img = str_replace('data:image/png;base64,', '', $img);
                        $img = str_replace(' ', '+', $img);
                        $data = base64_decode($img);

                        $filename = uniqid() . '.png';
                        $file = UPLOAD_DIR . $filename;
                        $success = file_put_contents($file, $data);

                        $adsImage->image_base_url = $success ? $fileStorage->baseUrl : '';
                        $adsImage->image_path = $success ? 'shares/' . $filename : '';
                        $adsImage->save();

                        if (!$model->thumbnail_base_url) {
                            $model->thumbnail_base_url = $fileStorage->baseUrl;
                            $model->thumbnail_path = 'shares/' . $filename;
                            $model->save(false);
                        }
                    }

                }
                // 3 Khi có ads phù hợp
//                $message = array('en'=>'Bạn có quảng cáo phù hợp với chuyên môn của bạn');
//                //$options = array( "include_player_ids"=> ["a7d48d0a-fa11-4cf8-a412-fc3f56388ad2"],"data"=> array("ads_id"=> $model->id),);
//                $filters = array();
//                foreach ($tags as $tag){
//                    $filters[] = array("field" => "tag", "key" => $tag, "relation" => "=", "value" => "1");
//                }
//                $options = array( 'filters' => $filters,"data"=> array("type"=>3,"ads_id"=> $model->id),"buttons"=>[array("id"=> "1", "text"=> "View","icon"=>"")]);
//                \Yii::$app->onesignal->notifications()->create($message, $options);

                return $model;
            } else {
                Yii::trace("Model validation error => " . print_r($model->getErrors(), true));
                $this->addError('generic', Yii::t('app', 'The system could not update the information.'));
            }
        }
        return false;
    }

    public function calculateShare()
    {
        $budget = $this->getRealBudget();
        if ($budget) {
            $price_unit = $this->getPriceUnit();
            return intval($budget / $price_unit);
        }

        return 0;

    }

    // Lấy tiền mà đã trừ phần trăm của hệ thống
    private function getRealMoney($share,$price)
    {
        return $share * $price;
    }

    // Lấy tiền mà đã trừ phần trăm của hệ thống
    private function getRealBudget()
    {
        if ($this->budget) {
            return $this->budget - ($this->budget * 0.2);
        }
        return 0;
    }

    private function getPriceUnit()
    {
        $price_base = \Yii::$app->keyStorage->get('config.price-basic', 5000);

        $price_unit = $price_base;
        if ($this->location && $this->location > 0) {
            $price_unit += $price_base * 0.1;
        }
        if ($this->age && $this->age > 0) {
            $price_unit += $price_base * 0.1;
        }

        if ($this->category && $this->category > 0) {
            $price_unit += $price_base * 0.1;
        }

        return $price_unit;
    }
}