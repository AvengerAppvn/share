<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "system_log_endpoint".
 *
 * @property int $id
 * @property string $action
 * @property string $method
 * @property string $param
 * @property string $header
 * @property int $created_at
 * @property int $updated_at
 */
class SystemLogEndpoint extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'system_log_endpoint';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['header','param','result'], 'string'],
            [['created_at', 'updated_at','count_time'], 'integer'],
            [['action', 'method'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'action' => 'Action',
            'method' => 'Method',
            'param' => 'Param',
            'header' => 'Header',
            'result' => 'Result',
            'count_time' => 'Time',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
