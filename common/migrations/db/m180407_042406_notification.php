<?php

use yii\db\Migration;

/**
 * Class m180407_042406_notification
 */
class m180407_042406_notification extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%notification}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull()->comment('Tiêu đề'),
            'description' => $this->integer()->notNull()->comment('Mô tả'),
            'user_id' => $this->integer()->notNull()->comment('Người dùng'),
            'ads_id' => $this->integer()->notNull()->comment('Quảng cáo'),
            'status' => $this->smallInteger(1)->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%notification}}');
    }
}
