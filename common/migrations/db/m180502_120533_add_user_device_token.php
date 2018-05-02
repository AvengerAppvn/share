<?php

use yii\db\Migration;

/**
 * Class m180502_120533_add_user_device_token
 */
class m180502_120533_add_user_device_token extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user_device_token', 'player_id', $this->string(255));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user_device_token', 'player_id');
    }
}
