<?php

namespace common\models;

use trntv\filekit\behaviors\UploadBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "advertise".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $cat_id
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property string $description
 * @property string $message
 * @property string $require
 * @property integer $share
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property string $thumbnail_base_url
 * @property string $thumbnail_path
 */
class Advertise extends \yii\db\ActiveRecord
{
    /**
     * @var array
     */
    public $thumbnail;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'advertise';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            BlameableBehavior::className(),
            [
                'class' => UploadBehavior::className(),
                'attribute' => 'thumbnail',
                'pathAttribute' => 'thumbnail_path',
                'baseUrlAttribute' => 'thumbnail_base_url'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cat_id', 'title'], 'required'],
            [['province_id', 'age_id','age_min','age_max', 'speciality_id', 'cat_id', 'share', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['content'], 'string'],
            [['require', 'message'], 'string', 'max' => 500],
            [['title', 'slug', 'description'], 'string', 'max' => 255],
            [['thumbnail_base_url', 'thumbnail_path'], 'string', 'max' => 1024],
            ['thumbnail', 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
//            'user_id' => Yii::t('common', 'User Id'),
            'cat_id' => Yii::t('common', 'Danh mục'),
            'title' => Yii::t('common', 'Tiêu đề'),
            'thumbnail' => Yii::t('common', 'Ảnh'),
            'thumbnail_base_url' => Yii::t('common', 'Ảnh'),
            'slug' => 'Slug',
            'content' => Yii::t('common', 'Nội dung'),
            'description' => Yii::t('common', 'Mô tả'),
            'message' => Yii::t('common', 'Thông điệp muốn chia sẻ'),
            'require' => Yii::t('common', 'Yêu cầu'),
            'share' => Yii::t('common', 'Số lượt share còn lại'),
            'total_share' => Yii::t('common', 'Đã share'),
            'status' => Yii::t('common', 'Trạng thái'),
            'created_at' => Yii::t('common', 'Ngày tạo'),
            'updated_at' => Yii::t('common', 'Ngày cập nhật'),
            'created_by' => Yii::t('common', 'Người tạo'),
            'updated_by' => Yii::t('common', 'Người cập nhật'),
            'province_id' => Yii::t('common', ' Khu vực'),
            'age_id' => Yii::t('common', ' Độ tuổi'),
            'speciality_id' => Yii::t('common', ' Chuyên ngành'),
            'budget' => Yii::t('common', ' Ngân sách'),
        ];
    }

    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getUpdater()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    public function getUrl()
    {
        return '/share' . $this->image_base_url . '/' . $this->image_path;
    }

    public function getCategory()
    {
        return $this->hasOne(AdsCategory::className(), ['id' => 'cat_id']);
    }

    public function getProvince()
    {
        return $this->hasOne(CriteriaProvince::className(), ['id' => 'province_id']);
    }

    public function getAge()
    {
        return $this->hasOne(CriteriaAge::className(), ['id' => 'age_id']);
    }

    public function getSpeciality()
    {
        return $this->hasOne(AdsCategory::className(), ['id' => 'speciality_id']);
    }
    /**
     * @inheritdoc
     * @return AdvertiseQuery the active query used by this AR class.
     */

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdvertiseImages()
    {
        return $this->hasMany(AdsAdvertiseImage::className(), ['ads_id' => 'id']);
    }

    public function getThumb()
    {
        return $this->thumbnail_base_url . '/' . $this->thumbnail_path;
    }
}
