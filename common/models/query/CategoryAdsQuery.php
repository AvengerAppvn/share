<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[CategoryAds]].
 *
 * @see CategoryAds
 */
class CategoryAdsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CategoryAds[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CategoryAds|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
