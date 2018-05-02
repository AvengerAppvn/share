<?php

use yii\db\Migration;

/**
 * Class m180502_131808_category_ads
 */
class m180502_131808_category_ads extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%category_ads}}', [
            'id' => $this->primaryKey(),
            'cat_id' => $this->integer()->notNull(),
            'ads_id' => $this->integer()->notNull(),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%category_ads}}');
    }
}
