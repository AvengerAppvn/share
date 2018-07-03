<?php

use yii\db\Migration;

/**
 * Class m180424_140335_add_user_profile
 */
class m180703_140335_add_face_book_user_profile extends Migration
{
    public function safeUp()
    {
        $this->addColumn('user', 'facebook', $this->string(500)->defaultValue(''));
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('user', 'facebook');
    }

}
