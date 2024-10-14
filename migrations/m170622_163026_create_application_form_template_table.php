<?php

use yii\db\Migration;

/**
 * Handles the creation of table `application_form_template`.
 */
class m170622_163026_create_application_form_template_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('application_form_template', [
            'id' => $this->primaryKey(),
            'application_type_id' => $this->integer()->notNull(),
            'application_form_file_id' => $this->integer()->notNull(),
            'archived' => $this->boolean()->notNull()->defaultValue(false),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull()
        ]);

        $this->createIndex(
            'idx-application_form_template-application_type_id',
            'application_form_template',
            'application_type_id'
        );

        $this->addForeignKey(
            'fk-application_form_template-application_type_id',
            'application_form_template',
            'application_type_id',
            'application_type',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-application_form_template-application_form_file_id',
            'application_form_template',
            'application_form_file_id'
        );

        $this->addForeignKey(
            'fk-application_form_template-application_form_file_id',
            'application_form_template',
            'application_form_file_id',
            'application_form_file',
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
            'fk-application_form_template-application_type_id',
            'application_form_template'
        );

        $this->dropIndex(
            'idx-application_form_template-application_type_id',
            'application_form_template'
        );

        $this->dropForeignKey(
            'fk-application_form_template-application_form_file_id',
            'application_form_template'
        );

        $this->dropIndex(
            'idx-application_form_template-application_form_file_id',
            'application_form_template'
        );

        $this->dropTable('application_form_template');
    }
}
