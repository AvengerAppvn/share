<?php

use yii\db\Migration;

/**
 * Class m180413_174451_add_advertise
 */
class m180413_174451_add_advertise extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('advertise', 'require', $this->string()->defaultValue(''));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('advertise', 'require');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180413_174451_add_advertise cannot be reverted.\n";

        return false;
    }
    */
}
