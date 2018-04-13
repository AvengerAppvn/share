<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ads_share".
 *
 * @property integer $id
 * @property integer $ads_id
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 */
class AdsShare extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ads_share';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ads_id'], 'required'],
            [['ads_id', 'user_id','created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ads_id' => 'Ads ID',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @inheritdoc
     * @return \common\models\query\AdsShareQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\AdsShareQuery(get_called_class());
    }

    public function getAdvertise()
    {
        return $this->hasOne(Advertise::className(), ['id' => 'ads_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
