<?php
namespace frontend\models;

use common\models\UserDeviceToken;
use common\models\AdsCategory;
use common\models\User;
use trntv\filekit\Storage;
use Yii;
use yii\base\Model;
use yii\di\Instance;

/**
 * User Edit form
 */
class UserEditForm extends Model
{
    public $id;
    public $password;
    public $email;
    public $address;
    public $phone;
    public $avatar;
    public $fullname;
    public $birthday;
    public $strengths;

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

            // if user email has been changed, then put the email in unconfirmed_email and set confirmed_at as null
            $updateIndicator = false;
            $updateProfile = false;

//            if ($this->_user->email != $this->email) {
//                //$this->_user->unconfirmed_email = $this->email;
//                //$this->_user->confirmed_at = null;
//                //$this->_user->status = User::STATUS_PENDING;
//                //$this->_user->generateAuthKey();
//                $updateIndicator = true;
//            }

            // If password is not null, then update password
//            if ($this->password) {
//                $updateIndicator = true;
//                $this->_user->setPassword($this->password);
//            }

            if ($this->phone) {
                $updateIndicator = true;
                $this->_user->phone = $this->phone;
            }

            if ($this->birthday && $this->_user->is_confirmed != 1) {
                $updateProfile = true;
                $this->_user->userProfile->birthday = date('Y-m-d', strtotime($this->birthday));
            }

            if ($this->address) {
                $updateProfile = true;
                $this->_user->userProfile->address = $this->address;
            }

            if ($this->strengths) {
                $updateProfile = true;
                $this->_user->userProfile->strengths = json_encode($this->strengths);
                // Get player_id
                $deviceTokens = UserDeviceToken::find()->where(['user_id'=>$this->id])->all();
                $options = array();
                foreach($this->strengths as $cat_id){
                    $cate = AdsCategory::findOne($cat_id);
                    if($cate){
                        $options[$cate->slug] = 1;
                    }
                }
                foreach($deviceTokens as $deviceToken){
                    // Add tag
                    \Yii::$app->onesignal->players($deviceToken->player_id)->addTag($options);
                }
            }

            if ($this->fullname && $this->_user->is_confirmed != 1) {
                $updateProfile = true;
                $this->_user->userProfile->fullname = $this->fullname;
            }

            if ($this->avatar) {
                $updateProfile = true;
                // requires php5
                define('UPLOAD_DIR',  \Yii::getAlias('@storage').'/web/source/avatar/');
                $fileStorage = Instance::ensure('fileStorage', Storage::className());

                $img = $this->avatar;
                $img = str_replace('data:image/png;base64,', '', $img);
                $img = str_replace(' ', '+', $img);
                $data = base64_decode($img);

                $filename = uniqid() . '.png';
                $file = UPLOAD_DIR . $filename;
                $success = file_put_contents($file, $data);
                $this->_user->userProfile->avatar_base_url = $success?$fileStorage->baseUrl.'/avatar' : '' ;
                $this->_user->userProfile->avatar_path = $success?$filename :'';
            }

            if ($updateProfile == true) {
                $this->_user->userProfile->save(false);
            }

            if ($updateIndicator == true && $this->_user->save(false)) {
                // Send confirmation email
                //$this->sendConfirmationEmail();
                return true;
            } elseif ($updateIndicator == false && $updateProfile == true && $this->_user->userProfile->save(false)) {
                // Nothing to update
                return true;
            } else {
                Yii::trace("Model validation error => " . print_r($this->_user->getErrors(), true));
                $this->addError('generic', Yii::t('app', 'The system could not update the information.'));
            }
        }
        return false;
    }


    public function sendConfirmationEmail()
    {

        $confirmURL = \Yii::$app->params['frontendURL'] . '#/confirm?id=' . $this->_user->id . '&auth_key=' . $this->_user->auth_key;

        $email = \Yii::$app->mailer
            ->compose(
                ['html' => 'email-confirmation-html'],
                [
                    'appName' => \Yii::$app->name,
                    'confirmURL' => $confirmURL,
                ]
            )
            ->setTo($this->email)
            ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
            ->setSubject('Email confirmation')
            ->send();

        return $email;
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