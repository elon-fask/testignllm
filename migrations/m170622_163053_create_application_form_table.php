<?php

use yii\db\Migration;

/**
 * Handles the creation of table `application_form`.
 */
class m170622_163053_create_application_form_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('application_form', [
            'id' => $this->primaryKey(),
            'application_form_template_id' => $this->integer()->notNull(),
            'application_form_file_id' => $this->integer()->notNull(),
            'candidate_id' => $this->integer()->notNull(),
            'test_session_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull()
        ]);

        $this->createIndex(
            'idx-application_form-application_form_template_id',
            'application_form',
            'application_form_template_id'
        );

        $this->addForeignKey(
            'fk-application_form-application_form_template_id',
            'application_form',
            'application_form_template_id',
            'application_form_template',
            'id'
        );

        $this->createIndex(
            'idx-application_form-application_form_file_id',
            'application_form',
            'application_form_file_id'
        );

        $this->addForeignKey(
            'fk-application_form-application_form_file_id',
            'application_form',
            'application_form_file_id',
            'application_form_file',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-application_form-candidate_id',
            'application_form',
            'candidate_id'
        );

        $this->addForeignKey(
            'fk-application_form-candidate_id',
            'application_form',
            'candidate_id',
            'candidates',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-application_form-test_session_id',
            'application_form',
            'test_session_id'
        );

        $this->addForeignKey(
            'fk-application_form-test_session_id',
            'application_form',
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
            'fk-application_form-test_session_id',
            'application_form'
        );

        $this->dropIndex(
            'idx-application_form-test_session_id',
            'application_form'
        );

        $this->dropForeignKey(
            'fk-application_form-candidate_id',
            'application_form'
        );

        $this->dropIndex(
            'idx-application_form-candidate_id',
            'application_form'
        );

        $this->dropForeignKey(
            'fk-application_form-application_form_file_id',
            'application_form'
        );

        $this->dropIndex(
            'idx-application_form-application_form_file_id',
            'application_form'
        );

        $this->dropForeignKey(
            'fk-application_form-application_form_template_id',
            'application_form'
        );

        $this->dropIndex(
            'idx-application_form-application_form_template_id',
            'application_form'
        );

        $this->dropTable('application_form');
    }
}
