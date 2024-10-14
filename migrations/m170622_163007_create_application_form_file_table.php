<?php

use yii\db\Migration;

/**
 * Handles the creation of table `application_form_file`.
 */
class m170622_163007_create_application_form_file_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('application_form_file', [
            'id' => $this->primaryKey(),
            'filename' => $this->string()->notNull()->unique(),
            'name' => $this->string()->notNull(),
            'description' => $this->text(),
            'archived' => $this->boolean()->notNull()->defaultValue(false),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('application_form_file');
    }
}
