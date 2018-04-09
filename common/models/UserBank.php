<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "user_bank".
 *
 * @property integer $id
 * @property string $account_name
 * @property string $account_number
 * @property integer $user_id
 * @property integer $bank_id
 * @property string $bank_name
 * @property integer $province_id
 * @property string $province_name
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
            [['account_name', 'account_number', 'bank_id', 'province_id', 'branch_name'], 'required'],
            [['user_id','bank_id', 'province_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['account_name', 'account_number' ,'branch_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => Yii::t('common', 'Tên chủ tài khoản'),
            'account_name' => Yii::t('common', 'Tên chủ tài khoản'),
            'account_number' => Yii::t('common', 'Số tài khoản'),
            'bank_id' => Yii::t('common', 'Tên ngân hàng'),
            'bank_name' => Yii::t('common', 'Tên ngân hàng'),
            'province_id' => Yii::t('common', 'Khu vực'),
            'province_name' => Yii::t('common', 'Khu vực'),
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

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
