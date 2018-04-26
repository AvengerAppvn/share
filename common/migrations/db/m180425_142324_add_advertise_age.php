<?php

use yii\db\Migration;

/**
 * Class m180425_142324_add_advertise_age
 */
class m180425_142324_add_advertise_age extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('advertise', 'age_min', $this->integer()->defaultValue(18));
        $this->addColumn('advertise', 'age_max', $this->integer()->defaultValue(80));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('advertise', 'age_max');
        $this->dropColumn('advertise', 'age_min');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180425_142324_add_advertise_age cannot be reverted.\n";

        return false;
    }
    */
}
