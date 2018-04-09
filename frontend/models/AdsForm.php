<?php
namespace frontend\models;

use common\models\AdsAdvertiseImage;
use common\models\Advertise;
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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['title', 'trim'],
            ['title', 'required','message'=> Yii::t('frontend','Missing title')],
            ['require', 'required','message'=> Yii::t('frontend','Missing require')],
            [['title','require','message'], 'string'],
            [['images','location','age','category'], 'safe']
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
            $model->description = $this->message;
            $model->cat_id = $this->category?:1;

            if ($model->save(false)) {
                $primaryKey = $model->getPrimaryKey();
                if ($this->images) {
                    // requires php5
                    define('UPLOAD_DIR',  \Yii::getAlias('@storage').'/web/source/shares/');
                    $fileStorage = Instance::ensure('fileStorage', Storage::className());

                    foreach($this->images as $image){
                        $adsImage = new AdsAdvertiseImage();
                        $adsImage->ads_id = $primaryKey;
                        $img = $image;
                        $img = str_replace('data:image/png;base64,', '', $img);
                        $img = str_replace(' ', '+', $img);
                        $data = base64_decode($img);

                        $filename = uniqid() . '.png';
                        $file = UPLOAD_DIR . $filename;
                        $success = file_put_contents($file, $data);
                        $baseUrl = $fileStorage->baseUrl.'/shares/'. $filename ;
                        $adsImage->image_path = $success ? $baseUrl : '';
                        $adsImage->save();

                        if(!$model->thumbnail_base_url){
                            $model->thumbnail_base_url = $fileStorage->baseUrl;
                            $model->thumbnail_path = 'shares/'. $filename;
                            $model->save(false);
                        }
                    }

                }
                return $model;
            } else {
                Yii::trace("Model validation error => " . print_r($model->getErrors(), true));
                $this->addError('generic', Yii::t('app', 'The system could not update the information.'));
            }
        }
        return false;
    }
}