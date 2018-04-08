<?php

use yii\db\Migration;

/**
 * Class m180408_064555_user_profile_verify
 */
class m180408_064555_user_profile_verify extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user_profile', 'image_id_1', $this->string());
        $this->addColumn('user_profile', 'image_id_2', $this->string());
        $this->addColumn('user_profile', 'image_friend_list', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user_profile', 'image_friend_list');
        $this->dropColumn('user_profile', 'image_id_2');
        $this->dropColumn('user_profile', 'image_id_1');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180408_064555_user_profile_verify cannot be reverted.\n";

        return false;
    }
    */
}
