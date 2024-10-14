<?php

use yii\db\Migration;

/**
 * Class m180423_124312_rename_session_ratings_table
 */
class m180423_124312_rename_session_ratings_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->renameColumn('candidate_session_ratings', 'candidateId', 'candidate_id');
        $this->renameColumn('candidate_session_ratings', 'testSessionId', 'test_session_id');
        $this->renameColumn('candidate_session_ratings', 'checkin', 'start_time');
        $this->renameColumn('candidate_session_ratings', 'checkout', 'end_time');
        $this->dropColumn('candidate_session_ratings', 'rating');
        $this->renameTable('candidate_session_ratings', 'candidate_training_session');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180423_124312_rename_session_ratings_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180423_124312_rename_session_ratings_table cannot be reverted.\n";

        return false;
    }
    */
}
