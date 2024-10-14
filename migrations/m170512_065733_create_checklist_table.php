<?php

use yii\db\Migration;

/**
 * Handles the creation for table `checklist_table`.
 */
class m170512_065733_create_checklist_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('checklist', [
            'id' => $this->primaryKey(),
            'test_session_id' => $this->integer(),
            'template_id' => $this->integer(),
            'name' => $this->string()->notNull(),
            'type' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime()
        ]);

        $this->createIndex(
            'idx-checklist-test_session_id',
            'checklist',
            'test_session_id'
        );

        $this->addForeignKey(
            'fk-checklist-test_session_id',
            'checklist',
            'test_session_id',
            'test_session',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-checklist-template_id',
            'checklist',
            'template_id'
        );

        $this->addForeignKey(
            'fk_checklist-template_id',
            'checklist',
            'template_id',
            'checklist_template',
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
            'fk-checklist-test_session_id',
            'checklist'
        );

        $this->dropIndex(
            'idx-checklist-test_session_id',
            'checklist'
        );

        $this->dropForeignKey(
            'fk_checklist-template_id',
            'checklist'
        );

        $this->dropIndex(
            'idx-checklist-template_id',
            'checklist'
        );

        $this->dropTable('checklist');
    }
}
