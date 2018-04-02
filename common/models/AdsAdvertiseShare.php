<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ads_advertise_share".
 *
 * @property integer $id
 * @property integer $ads_id
 * @property integer $province_id
 * @property integer $age_id
 * @property integer $speciality_id
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 *
 * @property Advertise $ads
 */
class AdsAdvertiseShare extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ads_advertise_share';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ads_id'], 'required'],
            [['ads_id', 'province_id', 'age_id', 'speciality_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['ads_id'], 'exist', 'skipOnError' => true, 'targetClass' => Advertise::className(), 'targetAttribute' => ['ads_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ads_id' => Yii::t('common', ' Ads Id'),
            'province_id' => Yii::t('common', ' Khu vực'),
            'age_id' =>Yii::t('common', ' Độ tuổi'),
            'speciality_id' => Yii::t('common', ' Độ tuổi'),
            'status' => Yii::t('common', ' Trạng thái'),
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
     * @return \common\models\query\AdsAdvertiseShareQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\AdsAdvertiseShareQuery(get_called_class());
    }
}
