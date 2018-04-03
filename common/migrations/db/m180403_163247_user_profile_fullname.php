<?php

use yii\db\Migration;

/**
 * Class m180403_163247_user_profile_fullname
 */
class m180403_163247_user_profile_fullname extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'status_confirmed', $this->smallInteger()->defaultValue(0));
        $this->addColumn('user_profile', 'fullname', $this->string());
        $this->addColumn('user_profile', 'strengths', $this->string());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user_profile', 'strengths');
        $this->dropColumn('user_profile', 'fullname');
        $this->dropColumn('user', 'status_confirmed');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180403_163247_user_profile_fullname cannot be reverted.\n";

        return false;
    }
    */
}
