<?php

use yii\db\Migration;

/**
 * Class m180329_071231_transaction
 */
class m180329_071231_transaction extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%transaction}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'amount' => $this->decimal(),
            'description' => $this->string()->notNull(),
            'type' => $this->smallInteger()->notNull()->comment('Loại giao dịch: 1. Thu, 2. Chi, 3. Đang chờ'),
            'status' => $this->smallInteger(1)->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%transaction}}');
    }
}
