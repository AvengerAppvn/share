<?php

use yii\db\Migration;

/**
 * Class m180412_030653_add_ads_advertise_share
 */
class m180412_030653_add_ads_advertise_share extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('ads_advertise_share', 'user_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('ads_advertise_share', 'user_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180412_030653_add_ads_advertise_share cannot be reverted.\n";

        return false;
    }
    */
}
