<?php

use yii\db\Migration;

/**
 * Handles the creation of table `candidate_decline_test_attestation`.
 */
class m180531_041011_create_candidate_decline_test_attestation_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('candidate_decline_test_attestation', [
            'id' => $this->primaryKey(),
            'candidate_id' => $this->integer()->notNull(),
            'test_session_id' => $this->integer()->notNull(),
            'crane' => $this->string()->notNull(),
            's3_key' => $this->string()->notNull(),
            'created_at' => $this->dateTime()
        ]);

        $this->createIndex(
            'idx-decline_test-candidate_id',
            'candidate_decline_test_attestation',
            'candidate_id'
        );

        $this->addForeignKey(
            'fk-decline_test-candidate_id',
            'candidate_decline_test_attestation',
            'candidate_id',
            'candidates',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-decline_test-test_session_id',
            'candidate_decline_test_attestation',
            'test_session_id',
            'test_session',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey(
            'fk-decline_test-test_session_id',
            'candidate_decline_test_attestation'
        );

        $this->dropForeignKey(
            'fk-decline_test-candidate_id',
            'candidate_decline_test_attestation'
        );

        $this->dropIndex(
            'idx-decline_test-candidate_id',
            'candidate_decline_test_attestation'
        );

        $this->dropTable('candidate_decline_test_attestation');
    }
}
