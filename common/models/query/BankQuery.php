<?php

namespace common\models\query;

/**
 * This is the ActiveQuery class for [[\common\models\Bank]].
 *
 * @see \common\models\Bank
 */
class BankQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \common\models\Bank[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\Bank|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
