<?php

use yii\db\Migration;

/**
 * Class m180424_140335_add_user_profile
 */
class m180424_140335_add_user_profile extends Migration
{
    public function safeUp()
    {
        $this->addColumn('user_profile', 'cmt', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('user_profile', 'cmt');
    }

}
