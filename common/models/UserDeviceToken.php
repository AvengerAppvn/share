<?php

namespace common\models;

use common\models\query\UserDeviceTokenQuery;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%user_device_token}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $type
 * @property string $token
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 */
class UserDeviceToken extends ActiveRecord
{
    const TOKEN_LENGTH = 255;
    const TYPE_ANDROID = 1;
    const TYPE_IOS = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_device_token';
    }

    /**
     * @return UserTokenQuery
     */
    public static function find()
    {
        return new UserDeviceTokenQuery(get_called_class());
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className()
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'type', 'token'], 'required'],
            [['user_id', 'expire_at'], 'integer'],
            [['token'], 'string', 'max' => self::TOKEN_LENGTH]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'user_id' => Yii::t('common', 'User ID'),
            'type' => Yii::t('common', 'Type'),
            'token' => Yii::t('common', 'Token'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }
}
