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
    }

    public function down()
    {
        $this->dropColumn('user', 'is_customer');
        $this->dropColumn('user', 'is_advertiser');
    }
}
