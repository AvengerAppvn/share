<?php
namespace frontend\models;

use common\models\User;
use trntv\filekit\Storage;
use Yii;
use yii\base\Model;
use yii\di\Instance;

/**
 * User Edit form
 */
class UserVerifyForm extends Model
{
    public $image_id_1;
    public $image_id_2;
    public $image_friend_list;

    /** @var User */
    private $_user = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['id', 'exist', 'targetClass' => '\common\models\User', 'filter' => [
                'and', ['status' => User::STATUS_ACTIVE],
            ], 'message' => 'The ID is not valid.'],
            ['email', 'trim'],
            //['email', 'required'],
            ['email', 'email'],
            ['address', 'string'],
            ['phone', 'string'],
            ['fullname', 'string'],
            //['avatar', 'string'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => Yii::t('app', 'This email address has already been taken.'), 'filter' => function ($query) {
                $query->andWhere(['!=', 'id', $this->id]);
            }],
            ['strengths', 'safe'],
            ['birthday', 'safe'],
            ['avatar', 'safe'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Signs user up.
     *
     * @return boolean the saved model or null if saving fails
     */
    public function save()
    {
        if ($this->validate()) {
            $this->getUserByID();

            $updateProfile = false;
            define('UPLOAD_DIR',  \Yii::getAlias('@storage').'/web/source/verify/');
            $fileStorage = Instance::ensure('fileStorage', Storage::className());

            $profile = $this->_user->userProfile;

            if ($this->image_id_1) {
                $updateProfile = true;

                $img = $this->image_id_1;
                $img = str_replace('data:image/png;base64,', '', $img);
                $img = str_replace(' ', '+', $img);
                $data = base64_decode($img);

                $filename = uniqid() . '.png';
                $file = UPLOAD_DIR . $filename;
                $success = file_put_contents($file, $data);
                $profile->image_id_1 = $success?$fileStorage->baseUrl.'/verify/'.$filename : '' ;
            }

            if ($this->image_id_2) {
                $updateProfile = true;

                $img = $this->image_id_2;
                $img = str_replace('data:image/png;base64,', '', $img);
                $img = str_replace(' ', '+', $img);
                $data = base64_decode($img);

                $filename = uniqid() . '.png';
                $file = UPLOAD_DIR . $filename;
                $success = file_put_contents($file, $data);
                $profile->image_id_1 = $success?$fileStorage->baseUrl.'/verify/'.$filename : '' ;
            }

            if ($this->image_friend_list) {
                $updateProfile = true;

                $img = $this->image_friend_list;
                $img = str_replace('data:image/png;base64,', '', $img);
                $img = str_replace(' ', '+', $img);
                $data = base64_decode($img);

                $filename = uniqid() . '.png';
                $file = UPLOAD_DIR . $filename;
                $success = file_put_contents($file, $data);
                $profile->image_id_1 = $success?$fileStorage->baseUrl.'/verify/'.$filename : '' ;
            }

            if ($updateProfile == true && $profile->save(false)) {
                return true;
            } else {
                Yii::trace("Model validation error => " . print_r($this->_user->getErrors(), true));
                $this->addError('generic', Yii::t('app', 'The system could not update the information.'));
            }
        }
        return false;
    }

    /**
     * Finds user by [[id]]
     *
     * @return User|null
     */
    public function getUserByID()
    {

        if ($this->_user === false) {
            $this->_user = User::findOne($this->id);
        }

        return $this->_user;
    }

}