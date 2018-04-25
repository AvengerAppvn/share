<?php

use yii\db\Migration;

/**
 * Class m180422_134508_edit_user
 */
class m180422_134508_edit_user extends Migration
{
    public function safeUp()
    {
        $this->dropColumn('user', 'is_confirmed');
        $this->addColumn('user', 'is_confirmed', $this->smallInteger(1));
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('user', 'is_confirmed');
    }

}
