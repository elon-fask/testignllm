<?php

use yii\db\Migration;

/**
 * Handles adding grade to table `candidate_training_session`.
 */
class m180607_114804_add_grade_column_to_candidate_training_session_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('candidate_training_session', 'grade', $this->integer()->after('test_session_id'));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('candidate_training_session', 'grade');
    }
}
