<?php

namespace frontend\modules\api\v1\resources;

use yii\helpers\Url;
use yii\web\Linkable;
use yii\web\Link;

/**
 * @author Eugene Terentev <eugene@terentev.net>
 */
class CampaignUpdate extends \common\models\Advertise
{
    public function fields()
    {
        return ['id','title','description','thumb','updated_at','status'];
    }

    public function extraFields()
    {
        return [];
    }
}
