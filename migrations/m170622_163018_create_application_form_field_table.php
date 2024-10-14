<?php

use yii\db\Migration;

/**
 * Handles the creation of table `application_form_field`.
 */
class m170622_163018_create_application_form_field_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('application_form_field', [
            'id' => $this->primaryKey(),
            'application_form_file_id' => $this->integer()->notNull(),
            'pdf_label' => $this->string()->notNull(),
            'type' => $this->string()->notNull(),
            'archived' => $this->boolean()->notNull()->defaultValue(false),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull()
        ]);

        $this->createIndex(
            'idx-application_form_field-application_form_file_id',
            'application_form_field',
            'application_form_file_id'
        );

        $this->addForeignKey(
            'fk-application_form_field-application_form_file_id',
            'application_form_field',
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
            'fk-application_form_field-application_form_file_id',
            'application_form_field'
        );

        $this->dropIndex(
            'idx-application_form_field-application_form_file_id',
            'application_form_field'
        );

        $this->dropTable('application_form_field');
    }
}
