<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[RequireCustomer]].
 *
 * @see RequireCustomer
 */
class RequireCustomerQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return RequireCustomer[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return RequireCustomer|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
