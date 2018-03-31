<?php

use yii\db\Migration;

/**
 * Class m180329_050649_advertise
 */
class m180329_050649_advertise extends Migration
{
    public function safeUp()
    {

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%ads_category}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string(),
            'description' => $this->string(),
            'image_base_url' => $this->string(1024),
            'image_path' => $this->string(1024),
            'status' => $this->smallInteger(1)->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ], $tableOptions);

        $this->createTable('{{%advertise}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'cat_id' => $this->integer()->notNull(),
            'title' => $this->string()->notNull(),
            'slug' => $this->string(),
            'content' => $this->text()->comment('Nội dung quảng cáo'),
            'description' => $this->string()->comment('Mô tả quảng cáo'),
            'message' => $this->string()->comment('thông điệp quảng cáo'),
            'share' => $this->smallInteger(1)->defaultValue(0)->comment('1: đã share, 0: chưa share'),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ], $tableOptions);

        $this->addForeignKey('fk_cat', '{{%advertise}}', 'cat_id', '{{%ads_category}}', 'id', 'cascade', 'cascade');

        $this->createTable('{{%ads_advertise_image}}', [
            'id' => $this->primaryKey(),
            'ads_id' => $this->integer()->notNull()->comment('ID quảng cáo'),
            'image_base_url' => $this->string(1024),
            'image_path' => $this->string(1024),
            'description' => $this->string()->comment('Mô tả ảnh quảng cáo'),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ], $tableOptions);

        $this->addForeignKey('fk_ads_image', '{{%ads_advertise_image}}', 'ads_id', '{{%advertise}}', 'id', 'cascade', 'cascade');


        //tiêu chí share quảng cáo
        $this->createTable('{{%ads_advertise_share}}', [
            'id' => $this->primaryKey(),
            'ads_id' => $this->integer()->notNull()->comment('ID quảng cáo'),
            'province_id' => $this->integer()->comment('ID khu vực'),
            'age_id' => $this->integer()->comment('ID độ tuổi'),
            'speciality_id' => $this->integer()->comment('ID chuyên môn'),
            'status' => $this->smallInteger()->defaultValue(0)->comment('1: đã share, 0: chưa share'),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ], $tableOptions);

        $this->addForeignKey('fk_ads_share', '{{%ads_advertise_share}}', 'ads_id', '{{%advertise}}', 'id', 'cascade', 'cascade');

        // Quảng cáo đã được share
        $this->createTable('{{%ads_share}}', [
            'id' => $this->primaryKey(),
            'ads_id' => $this->integer()->notNull()->comment('ID quảng cáo'),
            'user_id' => $this->integer()->comment('ID Người share'),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ], $tableOptions);

        $this->addForeignKey('fk_share', '{{%ads_share}}', 'ads_id', '{{%advertise}}', 'id', 'cascade', 'cascade');

    }

    public function down()
    {
        $this->dropForeignKey('fk_share', '{{%ads_share}}');
        $this->dropForeignKey('fk_ads_share', '{{%ads_advertise_share}}');
        $this->dropForeignKey('fk_ads_image', '{{%ads_advertise_image}}');
        $this->dropForeignKey('fk_cat', '{{%advertise}}');
        $this->dropTable('{{%ads_share}}');
        $this->dropTable('{{%ads_advertise_share}}');
        $this->dropTable('{{%ads_advertise_image}}');
        $this->dropTable('{{%advertise}}');
        $this->dropTable('{{%ads_category}}');

    }
}
