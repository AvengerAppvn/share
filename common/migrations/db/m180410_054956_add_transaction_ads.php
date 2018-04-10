<?php

use yii\db\Migration;

/**
 * Class m180410_054956_add_transaction_ads
 */
class m180410_054956_add_transaction_ads extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('transaction', 'ads_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('transaction', 'ads_id');
    }
    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180410_054956_add_transaction_ads cannot be reverted.\n";

        return false;
    }
    */
}
