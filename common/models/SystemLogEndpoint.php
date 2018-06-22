<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "system_log_endpoint".
 *
 * @property int $id
 * @property string $action
 * @property string $method
 * @property string $param
 * @property int $created_at
 * @property int $updated_at
 */
class SystemLogEndpoint extends base\SystemLogEndpoint
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
}
