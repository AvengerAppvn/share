<?php

use yii\db\Migration;

/**
 * Class m180622_150104_add_logs_ads
 */
class m180622_150104_add_logs_ads extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('advertise', 'logs', $this->text());
        $this->addColumn('advertise', 'ads_type', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('advertise', 'logs');
        $this->dropColumn('advertise', 'ads_type');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180622_150104_add_logs_ads cannot be reverted.\n";

        return false;
    }
    */
}
