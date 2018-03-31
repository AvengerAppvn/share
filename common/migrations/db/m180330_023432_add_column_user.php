<?php

use yii\db\Migration;

/**
 * Class m180330_023432_add_column_user
 */
class m180330_023432_add_column_user extends Migration
{
    public function safeUp()
    {
        $this->addColumn('user', 'is_customer', $this->boolean()->after('status'));
        $this->addColumn('user', 'is_advertiser', $this->boolean()->after('is_customer'));
        $this->addColumn('user', 'unconfirmed_email', $this->string()->after('is_advertiser'));
        $this->addColumn('user', 'is_confirmed', $this->string());

        $this->addColumn('user_profile', 'address', $this->string());
        $this->addColumn('user_profile', 'birthday', $this->date());
    }

    public function down()
    {
        $this->dropColumn('user_profile', 'birthday');
        $this->dropColumn('user_profile', 'address');
        $this->dropColumn('user', 'unconfirmed_email');
        $this->dropColumn('user', 'is_advertiser');
        $this->dropColumn('user', 'is_customer');
    }
}
