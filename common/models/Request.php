<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "request".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $amount
 * @property string $description
 * @property integer $type
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property string $image_base_url
 * @property string $image_path
 */
class Request extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'request';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            BlameableBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'description', 'type'], 'required'],
            [['user_id', 'type', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['amount'], 'number'],
            [['description'], 'string', 'max' => 255],
            [['image_base_url', 'image_path'], 'string', 'max' => 1024],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => Yii::t('common', 'Người yêu cầu'),
            'amount' => Yii::t('common', 'Số tiền'),
            'description' => Yii::t('common', 'Mô tả giao dịch'),
            'type' => Yii::t('common', 'Loại giao dịch'),
            'status' => Yii::t('common', 'Trạng thái'),
            'created_at' => Yii::t('common', 'Ngày tạo'),
            'updated_at' => Yii::t('common', 'Ngày cập nhật'),
            'created_by' => Yii::t('common', 'Người tạo'),
            'updated_by' => Yii::t('common', 'Người cập nhật'),
        ];
    }

    /**
     * @inheritdoc
     * @return \common\models\query\RequestQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\RequestQuery(get_called_class());
    }

    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getUpdater()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
