<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "time".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property int $number_day Số ngày
 * @property int $number_hour Số giờ
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 */
class Time extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'time';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['number_day', 'number_hour', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['title', 'description'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'number_day' => 'Number Day',
            'number_hour' => 'Number Hour',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * {@inheritdoc}
     * @return TimeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TimeQuery(get_called_class());
    }
}
