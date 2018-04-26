<?php

use yii\db\Migration;

/**
 * Class m180426_112234_add_bank_thumb
 */
class m180426_112234_add_bank_thumb extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bank', 'thumbnail_base_url', $this->string(1024));
        $this->addColumn('bank', 'thumbnail_path', $this->string(1024));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('bank', 'thumbnail_base_url');
        $this->dropColumn('bank', 'thumbnail_path');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180426_112234_add_bank_thumb cannot be reverted.\n";

        return false;
    }
    */
}
