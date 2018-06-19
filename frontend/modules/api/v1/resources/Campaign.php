<?php

namespace frontend\modules\api\v1\resources;

use yii\helpers\Url;
use yii\web\Linkable;
use yii\web\Link;

/**
 * @author Eugene Terentev <eugene@terentev.net>
 */
class Campaign extends \common\models\Advertise
{
    public function fields()
    {
        return ['id','title','description','status'];
    }

    public function extraFields()
    {
        return [];
    }
}
