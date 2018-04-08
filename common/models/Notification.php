<?php

namespace common\models;
use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "notification".
 *
 * @property integer $id
 * @property string $type
 * @property string $user_id
 * @property string $ads_id
 * @property string $title
 * @property string $description
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class Notification extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notification';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'description'], 'string'],
            [['user_id','ads_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            //'type' => 'Type',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
 /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    /**
     * @inheritdoc
     * @return \common\models\query\NotificationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\NotificationQuery(get_called_class());
    }
}
