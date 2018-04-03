<?php

use yii\db\Migration;

/**
 * Class m180403_131550_add_transaction
 */
class m180403_131550_add_transaction extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('transaction', 'logtime', $this->timestamp()->after('status')->comment('Thời gian giao dịch'));
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('transaction', 'logtime');
    }

}
