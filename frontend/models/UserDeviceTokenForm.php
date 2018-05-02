<?php
namespace frontend\models;

use common\models\User;
use common\models\UserDeviceToken;
use Yii;
use yii\base\Model;

/**
 * User Edit form
 */
class UserDeviceTokenForm extends Model
{
    public $type;
    public $token;
    public $player_id;
    public $id;

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
            ], 'message' => 'The User_ID is not valid.'],
            [['token', 'type'], 'required'],
            ['player_id', 'safe'],
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
            $deviceToken = new UserDeviceToken();
            $deviceToken->user_id = $this->id;
            $deviceToken->type = $this->type;
            $deviceToken->token = $this->token;
            $deviceToken->player_id = $this->player_id;
            if ($deviceToken->save(false)) {
                return true;
            } else {
                Yii::trace("Model validation error => " . print_r($deviceToken->getErrors(), true));
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