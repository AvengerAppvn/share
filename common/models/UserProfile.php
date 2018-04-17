<?php

namespace common\models;

use trntv\filekit\behaviors\UploadBehavior;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_profile".
 *
 * @property integer $user_id
 * @property integer $locale
 * @property string $firstname
 * @property string $middlename
 * @property string $lastname
 * @property string $picture
 * @property string $avatar
 * @property string $avatar_path
 * @property string $avatar_base_url
 * @property integer $gender
 * @property integer $country_id
 * @property string $fullname
 * @property string $address
 * @property date $birthday
 * @property string $image_id_1
 * @property string $image_id_2
 * @property string $image_friend_list
 *
 * @property User $user
 * @property Country $country
 */
class UserProfile extends ActiveRecord
{
    const GENDER_MALE = 1;
    const GENDER_FEMALE = 2;

    /**
     * @var
     */
    public $address;
    public $picture;

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'picture' => [
                'class' => UploadBehavior::className(),
                'attribute' => 'picture',
                'pathAttribute' => 'avatar_path',
                'baseUrlAttribute' => 'avatar_base_url'
            ]
        ];
    }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_profile}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'gender','country_id'], 'integer'],
            [['gender'], 'in', 'range' => [NULL, self::GENDER_FEMALE, self::GENDER_MALE]],
            [['fullname', 'firstname', 'middlename', 'lastname', 'avatar_path', 'avatar_base_url','image_friend_list','image_id_1','image_id_2'], 'string', 'max' => 255],
            ['locale', 'default', 'value' => Yii::$app->language],
            ['locale', 'in', 'range' => array_keys(Yii::$app->params['availableLocales'])],
            ['picture', 'safe'],
            ['strengths', 'safe'],
            //['birthday', 'date']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('common', 'User ID'),
            'firstname' => Yii::t('common', 'Tên'),
            'middlename' => Yii::t('common', 'Tên đệm'),
            'lastname' => Yii::t('common', 'Họ'),
            'locale' => Yii::t('common', 'Ngôn ngữ'),
            'country_id' => Yii::t('common', 'Quốc gia'),
            'picture' => Yii::t('common', 'Ảnh đại diện'),
            'gender' => Yii::t('common', 'Giới tính'),
            'address' => Yii::t('common', 'Địa chỉ'),
            'fullname' => Yii::t('common', 'Tên đầy đủ'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['id' => 'country_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return null|string
     */
    public function getFullName()
    {
        if ($this->firstname || $this->lastname) {
            return implode(' ', [$this->firstname, $this->lastname]);
        }
        return '';
    }

    /**
     * @param null $default
     * @return bool|null|string
     */
    public function getAvatar($default = null)
    {
        return $this->avatar_path
            ? Yii::getAlias($this->avatar_base_url . '/' . $this->avatar_path)
            : $default;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }
}
