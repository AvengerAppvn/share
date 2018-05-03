<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "transaction".
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
class Transaction extends \yii\db\ActiveRecord
{
    const TYPE_DEPOSIT = 1;
    const TYPE_WITHDRAW = 2;
    const TYPE_PENDING = 3;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'transaction';
    }

    /**
     * @inheritdoc
     */
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
            [['user_id', 'type', 'status','ads_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['amount'], 'number'],
            [['logtime'], 'safe'],
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
            'user_id' => Yii::t('common', 'Người dùng'),
            'ads_id' => Yii::t('common', 'Quảng cáo'),
            'amount' => Yii::t('common', 'Số tiền giao dịch'),
            'description' => Yii::t('common', 'Mô tả'),
            'type' => Yii::t('common', 'Loại giao dịch'),
            'logtime' => Yii::t('app', 'Thời gian giao dịch'),
            'status' => Yii::t('common', 'Trạng thái'),
            'created_at' => Yii::t('common', 'Ngày tạo'),
            'updated_at' => Yii::t('common', 'Ngày cập nhật'),
            'created_by' => Yii::t('common', 'Người tạo'),
            'updated_by' => Yii::t('common', 'Người cập nhật'),
            'image_base_url' => 'Image Base Url',
            'image_path' => 'Image Path',
        ];
    }

    /**
     * @inheritdoc
     * @return \common\models\query\TransactionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\TransactionQuery(get_called_class());
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getImage()
    {
        return $this->image_base_url . '/' . $this->image_path;
    }
}
