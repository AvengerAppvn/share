<?php

namespace common\models\query;

/**
 * This is the ActiveQuery class for [[\common\models\AdsAdvertiseShare]].
 *
 * @see \common\models\AdsAdvertiseShare
 */
class AdsAdvertiseShareQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \common\models\AdsAdvertiseShare[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\AdsAdvertiseShare|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
