<?php

use yii\db\Migration;

/**
 * Handles the creation of table `application_form_field_value`.
 */
class m170622_183740_create_application_form_field_value_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('application_form_field_value', [
            'id' => $this->primaryKey(),
            'application_form_id' => $this->integer()->notNull(),
            'application_form_field_id' => $this->integer()->notNull(),
            'value' => $this->string()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull()
        ]);

        $this->createIndex(
            'idx-application_form_field_value-application_form_id',
            'application_form_field_value',
            'application_form_id'
        );

        $this->addForeignKey(
            'fk-application_form_field_value-application_form_id',
            'application_form_field_value',
            'application_form_id',
            'application_form',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-application_form_field_value-application_form_field_id',
            'application_form_field_value',
            'application_form_field_id'
        );

        $this->addForeignKey(
            'fk-application_form_field_value-application_form_field_id',
            'application_form_field_value',
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
            'fk-application_form_field_value-application_form_field_id',
            'application_form_field_value'
        );

        $this->dropIndex(
            'idx-application_form_field_value-application_form_field_id',
            'application_form_field_value'
        );

        $this->dropForeignKey(
            'fk-application_form_field_value-application_form_id',
            'application_form_field_value'
        );

        $this->dropIndex(
            'idx-application_form_field_value-application_form_id',
            'application_form_field_value'
        );

        $this->dropTable('application_form_field_value');
    }
}
