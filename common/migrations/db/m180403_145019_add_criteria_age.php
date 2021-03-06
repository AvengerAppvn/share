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
        $this->addColumn('criteria_age', 'start_age', $this->integer());
        $this->addColumn('criteria_age', 'end_age', $this->integer());
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
