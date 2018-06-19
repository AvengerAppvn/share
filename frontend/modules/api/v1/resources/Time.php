<?php

namespace frontend\modules\api\v1\resources;

/**
 */
class Time extends \common\models\Time
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
