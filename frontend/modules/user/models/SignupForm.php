<?php
namespace frontend\modules\user\models;

use cheatsheet\Time;
use common\commands\SendEmailCommand;
use common\models\User;
use common\models\UserToken;
use frontend\modules\user\Module;
use yii\base\Exception;
use yii\base\Model;
use Yii;
use yii\helpers\Url;

/**
 * Signup form
 */
class SignupForm extends Model
{
    /**
     * @var
     */
    public $username;
    /**
     * @var
     */
    public $email;
    /**
     * @var
     */
    public $password;

    /**
     * @var
     */
    public $is_customer;

    /**
     * @var
     */
    public $is_advertiser;

    /** @var User */
    private $_user = false;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            //['username', 'required'],
//            ['username', 'unique',
//                'targetClass'=>'\common\models\User',
//                'message' => Yii::t('frontend', 'This username has already been taken.')
//            ],
            //['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique',
                'targetClass'=> '\common\models\User',
                'message' => Yii::t('frontend', 'This email address has already been taken.')
            ],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            [['is_advertiser', 'is_customer'], 'safe'],
            [['is_advertiser', 'is_customer'], 'boolean'],

            ['is_customer', function ($attribute, $params) {
                if (!$this->is_customer && !$this->is_advertiser) {
                    $this->addError($attribute, Yii::t('frontend', 'Missing field is_customer or is_advertiser.'));
                }
            }, 'skipOnEmpty' => false, 'skipOnError' => false],

        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'username'=>Yii::t('frontend', 'Username'),
            'email'=>Yii::t('frontend', 'E-mail'),
            'password'=>Yii::t('frontend', 'Password'),
        ];
    }
    /**
     * Signs user up.
     *
     * @return boolean the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {

            $user = new User();
            $user->username = strtolower($this->username);
            $user->email = $this->email;
            $user->is_customer = $this->is_customer;
            $user->is_advertiser = $this->is_advertiser;
            // TODO Zing thêm field này vào migrate cho bảng User
            //$user->unconfirmed_email = $this->email;
            $user->role = User::ROLE_USER;

            $user->status = User::STATUS_ACTIVE;
            //$user->status = User::STATUS_PENDING;
            $user->setPassword($this->password);
            $user->generateAuthKey();

            $user->registration_ip = Yii::$app->request->userIP;

            if($user->save(false)) {
                $this->_user = $user;
                $user->afterSignup();
                return $user->id;
            }

            return false;
        }
        return false;
    }
    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup1()
    {
        $this->username = $this->email;
        if ($this->validate()) {
            $shouldBeActivated = $this->shouldBeActivated();
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->status = $shouldBeActivated ? User::STATUS_NOT_ACTIVE : User::STATUS_ACTIVE;
            $user->setPassword($this->password);
            if(!$user->save()) {
                throw new Exception("User couldn't be  saved");
            };
            $user->afterSignup();
            if ($shouldBeActivated) {
                $token = UserToken::create(
                    $user->id,
                    UserToken::TYPE_ACTIVATION,
                    Time::SECONDS_IN_A_DAY
                );
                Yii::$app->commandBus->handle(new SendEmailCommand([
                    'subject' => Yii::t('frontend', 'Activation email'),
                    'view' => 'activation',
                    'to' => $this->email,
                    'params' => [
                        'url' => Url::to(['/user/sign-in/activation', 'token' => $token->token], true)
                    ]
                ]));
            }
            return $user;
        }

        return null;
    }

    /**
     * @return bool
     */
    public function shouldBeActivated()
    {
        /** @var Module $userModule */
        $userModule = Yii::$app->getModule('user');
        if (!$userModule) {
            return false;
        } elseif ($userModule->shouldBeActivated) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Return User object
     *
     * @return User
     */
    public function getUser(){
        return $this->_user;
    }


    public function sendConfirmationEmail(){

        $confirmURL = \Yii::getAlias('@frontendUrl').'#/confirm?id='.$this->_user->id.'&auth_key='.$this->_user->auth_key;

        $email = \Yii::$app->mailer
            ->compose(
                ['html' =>  'signup-confirmation-html'],
                [
                    'appName'       =>  \Yii::$app->name,
                    'confirmURL'    =>  $confirmURL,
                ]
            )
            ->setTo($this->email)
            ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
            ->setSubject('Signup confirmation');
            //->send(); TODO REMOVE

        return $email;
    }
}
