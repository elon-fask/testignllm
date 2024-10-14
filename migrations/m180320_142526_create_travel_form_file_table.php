<?php

use yii\db\Migration;

/**
 * Handles the creation of table `travel_form_file`.
 */
class m180320_142526_create_travel_form_file_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('travel_form_file', [
            'id' => $this->primaryKey(),
            'travel_form_id' => $this->integer()->notNull(),
            'filename' => $this->string()->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime()
        ]);

        $this->addForeignKey(
            'fk-travel_form_file-travel_form_id',
            'travel_form_file',
            'travel_form_id',
            'travel_form',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('travel_form_file');
    }
}
