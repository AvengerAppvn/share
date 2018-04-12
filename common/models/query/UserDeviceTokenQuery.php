<?php

namespace common\models\query;

use yii\db\ActiveQuery;

/**
 * Class UserDeviceTokenQuery
 * @package common\models\query
 * @author
 */
class UserDeviceTokenQuery extends ActiveQuery
{
    /**
     * @param $type
     * @return $this
     */
    public function byType($type)
    {
        $this->andWhere(['type' => $type]);
        return $this;
    }
    
    /**
     * @param $user_id
     * @return $this
     */
    public function byUser($user_id)
    {
        $this->andWhere(['user_id' => $user_id]);
        return $this;
    }
    
    /**
     * @param $token
     * @return $this
     */
    public function byToken($token)
    {
        $this->andWhere(['token' => $token]);
        return $this;
    }
}