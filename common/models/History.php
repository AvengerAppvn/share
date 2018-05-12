<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "history".
 *
 * @property int $id
 * @property int $user_id
 * @property string $amount
 * @property string $description
 * @property int $type Loại giao dịch: 1. Rút tiền, 2. nạp tiền
 * @property int $status 0: pending, 1: done
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 */
class History extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'history';
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
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'amount' => 'Amount',
            'description' => 'Description',
            'type' => 'Type',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @inheritdoc
     * @return \common\models\query\HistoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\HistoryQuery(get_called_class());
    }
}
