<?php

use yii\db\Migration;

/**
 * Class m180415_165645_add_avertise_fix_data_all
 */
class m180415_165645_add_avertise_fix_data_all extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('advertise', 'budget', $this->integer()->defaultValue(0));

        $this->insert('criteria_province', ['id' => 0, 'name' => 'Tất cả', 'slug' => 'tat-ca', 'status' => 1]);
        $this->insert('criteria_age', ['id' => 0, 'name' => 'Tất cả', 'slug' => 'tat-ca', 'status' => 1]);
        $this->insert('ads_category', ['id' => 0, 'name' => 'Tất cả', 'slug' => 'tat-ca', 'status' => 1]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('criteria_age',['id' => 0]);
        $this->delete('criteria_province',['id' => 0]);
        $this->delete('ads_category',['id' => 0]);
        $this->dropColumn('advertise', 'budget');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180415_165645_add_avertise_fix_data_all cannot be reverted.\n";

        return false;
    }
    */
}
