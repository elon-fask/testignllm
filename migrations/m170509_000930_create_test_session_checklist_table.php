<?php

use yii\db\Migration;

/**
 * Handles the creation for table `test_session_checklist_table`.
 */
class m170509_000930_create_test_session_checklist_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('test_session_checklist', [
            'test_session_id' => $this->integer()->notNull(),
            'checklist_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime(),
            'PRIMARY KEY(test_session_id, checklist_id)',
        ]);

        $this->createIndex(
            'idx-test_session-checklist',
            'test_session_checklist',
            'test_session_id'
        );

        $this->addForeignKey(
            'fk-test_session-checklist',
            'test_session_checklist',
            'test_session_id',
            'test_session',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-checklist-test_session',
            'test_session_checklist',
            'checklist_id'
        );

        $this->addForeignKey(
            'fk-checklist-test_session',
            'test_session_checklist',
            'checklist_id',
            'checklist',
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
            'fk-test_session-checklist',
            'test_session_checklist'
        );

        $this->dropIndex(
            'idx-test_session-checklist',
            'test_session_checklist'
        );

        $this->dropForeignKey(
            'fk-checklist-test_session',
            'test_session_checklist'
        );

        $this->dropIndex(
            'idx-checklist-test_session',
            'test_session_checklist'
        );

        $this->dropTable('test_session_checklist');
    }
}