<?php

use yii\db\Migration;

/**
 * Class m180329_045811_share_criteria
 */
class m180329_045811_share_criteria extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%criteria_province}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string(),
            'description' => $this->string(),
            'status' => $this->smallInteger(1)->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ], $tableOptions);

        $this->createTable('{{%criteria_age}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string(),
            'description' => $this->string(),
            'status' => $this->smallInteger(1)->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ], $tableOptions);

        $this->createTable('{{%criteria_speciality}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string(),
            'description' => $this->string(),
            'status' => $this->smallInteger(1)->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%criteria_speciality}}');
        $this->dropTable('{{%criteria_age}}');
        $this->dropTable('{{%criteria_province}}');

    }

}
