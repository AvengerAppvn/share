<?php

use yii\db\Migration;

/**
 * Class m180418_135200_add_ads_share
 */
class m180418_135200_add_ads_share extends Migration
{
    public function safeUp()
    {
        $this->addColumn('ads_share', 'status', $this->smallInteger(1));
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('ads_share', 'status');
    }
}
