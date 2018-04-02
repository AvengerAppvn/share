<?php

namespace common\models;

use Yii;

use trntv\filekit\behaviors\UploadBehavior;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "ads_category".
 *
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property string $image_base_url
 * @property string $image_path
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 */
class AdsCategory extends \yii\db\ActiveRecord
{
    public $image;

    /**
     * @inheritdoc
     */

    public static function tableName()
    {
        return 'ads_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['name', 'slug', 'description'], 'string', 'max' => 255],
            [['image_base_url', 'image_path'], 'string', 'max' => 1024],
            ['image', 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => Yii::t('common', 'Tên danh mục'),
            'slug' => Yii::t('common', 'Tên danh mục(ASCII)'),
            'description' => Yii::t('common', 'Mô tả'),
            'image_base_url' => Yii::t('common', 'Đường dẫn ảnh'),
            'image_path' => Yii::t('common', 'Tên ảnh'),
            'image' => Yii::t('common', 'Ảnh'),
            'status' => Yii::t('common', 'Trạng thái'),
            'created_at' => Yii::t('common', 'Ngày tạo'),
            'updated_at' => Yii::t('common', 'Ngày cập nhật'),
            'created_by' => Yii::t('common', 'Người tạo'),
            'updated_by' => Yii::t('common', 'Người cập nhật'),
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            BlameableBehavior::className(),
            [
                'class' => UploadBehavior::className(),
                'attribute' => 'image',
                'pathAttribute' => 'image_path',
                'baseUrlAttribute' => 'image_base_url'
            ],
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
        return $this->image_base_url . '/' . $this->image_path;
    }

    public function getThumbnail()
    {
        return $this->image_base_url . '/' . $this->image_path;
    }
    /**
     * @inheritdoc
     * @return AdsCategoryQuery the active query used by this AR class.
     */
}
