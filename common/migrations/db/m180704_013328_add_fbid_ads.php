<?php

use yii\db\Migration;

/**
 * Class m180704_013328_add_fbid_ads
 */
class m180704_013328_add_fbid_ads extends Migration
{
    /**
     * {@inheritdoc}
     */
	public function safeUp()
	{
		$this->addColumn('advertise', 'fbid', $this->text());
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropColumn('advertise', 'fbid');
	}

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180704_013328_add_fbid_ads cannot be reverted.\n";

        return false;
    }
    */
}
