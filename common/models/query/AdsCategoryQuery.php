<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[AdsCategory]].
 *
 * @see AdsCategory
 */
class AdsCategoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return AdsCategory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AdsCategory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
