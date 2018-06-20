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
        return ['id','title','description','thumb', 'created_by',

            'created_at'=> function($model){
                return date('Y-m-d H:i:s', $model->created_at);
            },
            'updated_at'=> function($model){
                return date('Y-m-d H:i:s', $model->updated_at);
            },
            'status',];
    }

    public function extraFields()
    {
        return ['thumb'];
    }
}
