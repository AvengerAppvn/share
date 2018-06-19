<?php

namespace frontend\modules\api\v1\resources;

/**
 * @author Eugene Terentev <eugene@terentev.net>
 */
class RequireCustomer extends \common\models\RequireCustomer
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
