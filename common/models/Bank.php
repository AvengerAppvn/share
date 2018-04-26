<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use trntv\filekit\behaviors\UploadBehavior;
/**
 * This is the model class for table "bank".
 *
 * @property integer $id
 * @property string $name
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property string $description
 * @property integer $fee_bank
 * @property string $thumbnail_base_url
 * @property string $thumbnail_path
 */
class Bank extends \yii\db\ActiveRecord
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
        return 'bank';
    }

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
            [['name'], 'required'],
            [['status', 'fee_bank', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['name', 'description'], 'string', 'max' => 255],
            [['thumbnail_base_url', 'thumbnail_path'], 'string', 'max' => 1024],
            [['thumbnail'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => Yii::t('common', 'Tên ngân hàng'),
            'description' => Yii::t('common', 'Mô tả'),
            'fee_bank' => Yii::t('common', 'Phí'),
            'status' => Yii::t('common', 'Trạng thái'),
            'created_at' => Yii::t('common', 'Ngày tạo'),
            'updated_at' => Yii::t('common', 'Ngày cập nhật'),
            'created_by' => Yii::t('common', 'Người tạo'),
            'updated_by' => Yii::t('common', 'Người cập nhật'),
            'thumbnail' => Yii::t('common', 'Thumbnail'),
        ];
    }

    /**
     * @inheritdoc
     * @return \common\models\query\BankQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\BankQuery(get_called_class());
    }

    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getUpdater()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    public function getThumb()
    {
        return $this->thumbnail_base_url . '/' . $this->thumbnail_path;
    }
}
