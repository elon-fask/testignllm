<?php

use yii\db\Migration;

/**
 * Class m180912_070016_remove_unused_columns_in_candidate_table
 */
class m180912_070016_remove_unused_columns_in_candidate_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->dropColumn('candidates', 'has1000Exp');
        $this->dropColumn('candidates', 'iaiSubmittedDate');
        $this->dropColumn('candidates', 'numberOfCranes');
        $this->dropColumn('candidates', 'photo');
        $this->dropColumn('candidates', 'writtenNumberOfCranes');
        $this->dropColumn('candidates', 'practicalNumberOfCranes');
        $this->dropColumn('candidates', 'isWrittenCancelled');
        $this->dropColumn('candidates', 'isPracticalCancelled');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180912_070016_remove_unused_columns_in_candidate_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180912_070016_remove_unused_columns_in_candidate_table cannot be reverted.\n";

        return false;
    }
    */
}
