<?php

use yii\db\Migration;

class m170617_191303_create_index_candidate_and_test_session_tables extends Migration
{
    public function safeUp()
    {
        $this->createIndex(
            'idx-candidate_session-candidate_id',
            'candidate_session',
            'candidate_id'
        );

        $this->addForeignKey(
            'fk-candidate_session-candidate_id',
            'candidate_session',
            'candidate_id',
            'candidates',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-candidate_session-test_session_id',
            'candidate_session',
            'test_session_id'
        );

        $this->addForeignKey(
            'fk-candidate_session-test_session_id',
            'candidate_session',
            'test_session_id',
            'test_session',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-candidate_session-candidate_id',
            'candidate_session'
        );

        $this->dropIndex(
            'idx-candidate_session-candidate_id',
            'candidate_session'
        );

        $this->dropForeignKey(
            'fk-candidate_session-test_session_id',
            'candidate_session'
        );

        $this->dropIndex(
            'idx-candidate_session-test_session_id',
            'candidate_session'
        );
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170617_191303_create_junction_candidate_testsession_tables cannot be reverted.\n";

        return false;
    }
    */
}
