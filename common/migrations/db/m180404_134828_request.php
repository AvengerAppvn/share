<?php

use yii\db\Migration;

/**
 * Class m180404_134828_request
 */
class m180404_134828_request extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%request}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'amount' => $this->decimal(),
            'description' => $this->string()->notNull(),
            'type' => $this->smallInteger()->notNull()->comment('Loại giao dịch: 1. Rút tiền, 2. nạp tiền'),
            'status' => $this->smallInteger(1)->defaultValue(0)->comment('0: pending, 1: done'),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%request}}');
    }
}
