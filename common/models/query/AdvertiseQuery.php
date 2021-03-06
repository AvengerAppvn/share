<?php

namespace common\models\query;

use common\models\Advertise;

/**
 * This is the ActiveQuery class for [[Advertise]].
 *
 * @see Advertise
 */
class AdvertiseQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        return $this->andWhere(['status'=>Advertise::STATUS_ACTIVE]);
    }
    public function pending()
    {
        return $this->andWhere(['status'=>Advertise::STATUS_PENDING]);
    }

    public function pause()
    {
        return $this->andWhere(['status'=>Advertise::STATUS_PAUSE]);
    }

    public function stop()
    {
        return $this->andWhere(['status'=>Advertise::STATUS_STOP]);
    }
    public function finish()
    {
        return $this->andWhere(['status'=>Advertise::STATUS_FINISH]);
    }

    public function pauseAndPending()
    {
        return $this->andWhere(['status'=>[Advertise::STATUS_PAUSE,Advertise::STATUS_PENDING]]);
    }
    /**
     * @inheritdoc
     * @return Advertise[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Advertise|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
