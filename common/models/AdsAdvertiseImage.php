<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "ads_advertise_image".
 *
 * @property integer $id
 * @property integer $ads_id
 * @property string $image_base_url
 * @property string $image_path
 * @property string $description
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 *
 * @property Advertise $ads
 */
class AdsAdvertiseImage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ads_advertise_image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ads_id'], 'required'],
            [['ads_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['image_base_url', 'image_path'], 'string', 'max' => 1024],
            [['description'], 'string', 'max' => 255],
            [['ads_id'], 'exist', 'skipOnError' => true, 'targetClass' => Advertise::className(), 'targetAttribute' => ['ads_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'updatedAtAttribute' => false
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ads_id' => 'Quảng cáo',
            'image_base_url' => 'Image Base Url',
            'image_path' => 'Image Path',
            'description' => 'Description',
            'status' => 'Trạng thái',
            'created_at' => Yii::t('common', 'Ngày tạo'),
            'updated_at' => Yii::t('common', 'Ngày cập nhật'),
            'created_by' => Yii::t('common', 'Người tạo'),
            'updated_by' => Yii::t('common', 'Người cập nhật'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAds()
    {
        return $this->hasOne(Advertise::className(), ['id' => 'ads_id']);
    }

    /**
     * @inheritdoc
     * @return \common\models\query\AdsAdvertiseImageQuery the active query used by this AR class.
     */
}
