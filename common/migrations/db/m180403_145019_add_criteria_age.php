<?php

use yii\db\Migration;

/**
 * Class m180403_145019_add_criteria_age
 */
class m180403_145019_add_criteria_age extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('criteria_age', 'start_age', $this->integer()->after('description'));
        $this->addColumn('criteria_age', 'end_age', $this->integer()->after('start_age'));
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('criteria_age', 'end_age');
        $this->dropColumn('criteria_age', 'start_age');
    }
}
