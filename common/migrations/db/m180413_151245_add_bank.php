<?php

use yii\db\Migration;

/**
 * Class m180413_151245_add_bank
 */
class m180413_151245_add_bank extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bank', 'description', $this->string());
        $this->addColumn('bank', 'fee_bank', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('bank', 'fee_bank');
        $this->dropColumn('bank', 'description');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180413_151245_add_bank cannot be reverted.\n";

        return false;
    }
    */
}
