<?php

use yii\db\Migration;

/**
 * Class m180404_141608_bank
 */
class m180404_141608_bank extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%bank}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->comment('Tên ngân hàng'),
            'status' => $this->smallInteger(1)->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%bank}}');
    }
}
