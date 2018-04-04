<?php

use yii\db\Migration;

/**
 * Class m180404_141855_user_bank
 */
class m180404_141855_user_bank extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user_bank}}', [
            'id' => $this->primaryKey(),
            'user_name' => $this->string()->notNull()->comment('Tên tài khoản'),
            'number' => $this->integer()->notNull()->comment('Số tài khoản'),
            'bank_id' => $this->integer()->notNull()->comment('Tên ngân hàng'),
            'province_id' => $this->integer()->notNull()->comment('Khu vực'),
            'branch_name' => $this->string()->notNull()->comment('Tên chi nhánh'),
            'status' => $this->smallInteger(1)->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%user_bank}}');
    }
}
