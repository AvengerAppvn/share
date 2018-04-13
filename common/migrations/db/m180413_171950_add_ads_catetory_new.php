<?php

use yii\db\Migration;

/**
 * Class m180413_171950_add_ads_catetory_new
 */
class m180413_171950_add_ads_catetory_new extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('ads_category', 'new', $this->integer()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('ads_category', 'new');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180413_171950_add_ads_catetory_new cannot be reverted.\n";

        return false;
    }
    */
}
