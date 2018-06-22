<?php

use yii\db\Migration;

/**
 * Class m180620_154405_add_price_on_share_ads
 */
class m180620_154405_add_price_on_share_ads extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('advertise', 'price_on_share', $this->float());
        $this->addColumn('advertise', 'budget_remain', $this->float());
        $this->addColumn('advertise', 'criteria', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('advertise', 'price_on_share');
        $this->dropColumn('advertise', 'budget_remain');
        $this->dropColumn('advertise', 'criteria');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180620_154405_add_price_on_share_ads cannot be reverted.\n";

        return false;
    }
    */
}
