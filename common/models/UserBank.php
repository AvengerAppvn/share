<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "user_bank1".
 *
 * @property integer $id
 * @property string $user_name
 * @property integer $number
 * @property integer $bank_id
 * @property integer $province_id
 * @property string $branch_name
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 */
class UserBank extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_bank';
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
            [['user_name', 'number', 'bank_id', 'province_id', 'branch_name'], 'required'],
            [['number', 'bank_id', 'province_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['user_name', 'branch_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_name' => Yii::t('common', 'Tên chủ tài khoản'),
            'number' => Yii::t('common', 'Số tài khoản'),
            'bank_id' => Yii::t('common', 'Tên ngân hàng'),
            'province_id' => Yii::t('common', 'Khu vực'),
            'branch_name' => Yii::t('common', 'Chi nhánh ngân hàng'),
            'status' => Yii::t('common', 'Trạng thái'),
            'created_at' => Yii::t('common', 'Ngày tạo'),
            'updated_at' => Yii::t('common', 'Ngày cập nhật'),
            'created_by' => Yii::t('common', 'Người tạo'),
            'updated_by' => Yii::t('common', 'Người cập nhật'),
        ];
    }

    /**
     * @inheritdoc
     * @return \common\models\query\UserBankQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\UserBankQuery(get_called_class());
    }

    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getUpdater()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    public function getProvince()
    {
        return $this->hasOne(CriteriaProvince::className(), ['id' => 'province_id']);
    }

    public function getBank()
    {
        return $this->hasOne(Bank::className(), ['id' => 'bank_id']);
    }
}
