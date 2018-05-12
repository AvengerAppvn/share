<?php

use yii\db\Migration;

/**
 * Class m180503_121233_add_transaction_image
 */
class m180512_121233_add_request_image extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('request', 'image_base_url', $this->string(1024));
        $this->addColumn('request', 'image_path', $this->string(1024));
    }
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('request', 'image_base_url');
        $this->dropColumn('request', 'image_path');
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
