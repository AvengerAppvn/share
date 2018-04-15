<?php

use yii\db\Migration;

/**
 * Class m180415_162456_add_share
 */
class m180415_162456_add_share extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('ads_share', 'post_id', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('ads_share', 'post_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180415_162456_add_share cannot be reverted.\n";

        return false;
    }
    */
}
