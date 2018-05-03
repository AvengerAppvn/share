<?php

use yii\db\Migration;

/**
 * Class m180503_121233_add_transaction_image
 */
class m180503_121233_add_transaction_image extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('transaction', 'image_base_url', $this->string(1024));
        $this->addColumn('transaction', 'image_path', $this->string(1024));
    }
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('transaction', 'image_base_url');
        $this->dropColumn('transaction', 'image_path');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180503_121233_add_transaction_image cannot be reverted.\n";

        return false;
    }
    */
}
