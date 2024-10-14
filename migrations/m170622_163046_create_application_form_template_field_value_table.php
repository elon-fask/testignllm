<?php

use yii\db\Migration;

/**
 * Handles the creation of table `application_form_template_field_value`.
 */
class m170622_163046_create_application_form_template_field_value_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('application_form_template_field_value', [
            'id' => $this->primaryKey(),
            'application_form_template_id' => $this->integer()->notNull(),
            'application_form_field_id' => $this->integer()->notNull(),
            'value' => $this->string()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull()
        ]);

        $this->createIndex(
            'idx-app_form_tmpl_field_val-app_form_tmpl_id',
            'application_form_template_field_value',
            'application_form_template_id'
        );

        $this->addForeignKey(
            'fk-app_form_tmpl_field_val-app_form_tmpl_id',
            'application_form_template_field_value',
            'application_form_template_id',
            'application_form_template',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-app_form_tmpl_field_val-app_form_field_id',
            'application_form_template_field_value',
            'application_form_field_id'
        );

        $this->addForeignKey(
            'fk-app_form_tmpl_field_val-app_form_field_id',
            'application_form_template_field_value',
            'application_form_field_id',
            'application_form_field',
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
            'fk-app_form_tmpl_field_val-app_form_tmpl_id',
            'application_form_template_field_value'
        );

        $this->dropIndex(
            'idx-app_form_tmpl_field_val-app_form_tmpl_id',
            'application_form_template_field_value'
        );

        $this->dropForeignKey(
            'fk-app_form_tmpl_field_val-app_form_field_id',
            'application_form_template_field_value'
        );

        $this->dropIndex(
            'idx-app_form_tmpl_field_val-app_form_field_id',
            'application_form_template_field_value'
        );

        $this->dropTable('application_form_template_field_value');
    }
}
