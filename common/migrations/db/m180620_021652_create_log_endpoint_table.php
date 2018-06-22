<?php

use yii\db\Migration;

/**
 * Handles the creation of table `log_endpoint`.
 */
class m180620_021652_create_log_endpoint_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('system_log_endpoint', [
            'id' => $this->bigPrimaryKey(),
            'action' => $this->string(),
            'method' => $this->string(),
            'header' => $this->text(),
            'param' => 'LONGTEXT',
            'result' => 'LONGTEXT',
            'count_time' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        $this->createIndex('idx_system_log_endpoint_action', 'system_log_endpoint', 'action');
        $this->createIndex('idx_system_log_endpoint_method', 'system_log_endpoint', 'method');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('system_log_endpoint');
    }
}


