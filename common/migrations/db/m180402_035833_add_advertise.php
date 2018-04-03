<?php

use yii\db\Migration;

/**
 * Class m180402_035833_add_advertise
 */
class m180402_035833_add_advertise extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('advertise', 'province_id', $this->integer());
        $this->addColumn('advertise', 'age_id', $this->integer());
        $this->addColumn('advertise', 'speciality_id', $this->integer());
        #$this->addColumn('ads_share', 'status', $this->smallInteger(1));
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        #$this->dropColumn('ads_share', 'status');
        $this->dropColumn('advertise', 'province_id');
        $this->dropColumn('advertise', 'age_id');
        $this->dropColumn('advertise', 'speciality_id');
    }

}
