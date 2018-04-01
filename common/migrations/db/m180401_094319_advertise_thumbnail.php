<?php

use yii\db\Migration;

/**
 * Class m180401_094319_advertise_thumbnail
 */
class m180401_094319_advertise_thumbnail extends Migration
{
    public function safeUp()
    {
        $this->addColumn('advertise', 'thumbnail_base_url',$this->string(1024));
        $this->addColumn('advertise', 'thumbnail_path', $this->string(1024));
    }

    public function down()
    {
        $this->dropColumn('advertise', 'thumbnail_base_url');
        $this->dropColumn('advertise', 'thumbnail_path');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180401_094319_advertise_thumbnail cannot be reverted.\n";

        return false;
    }
    */
}
