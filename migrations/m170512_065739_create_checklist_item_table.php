<?php

use yii\db\Migration;

/**
 * Handles the creation for table `checklist_item_table`.
 */
class m170512_065739_create_checklist_item_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('checklist_item', [
            'id' => $this->primaryKey(),
            'checklist_id' => $this->integer()->notNull(),
            'item_type' => $this->integer(),
            'name' => $this->string()->notNull(),
            'description' => $this->text(),
            'value' => $this->integer(),
            'failing_score' => $this->integer()->notNull(),
            'note' => $this->text(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime()
        ]);

        $this->createIndex(
            'idx-checklist_item-checklist_id',
            'checklist_item',
            'checklist_id'
        );

        $this->addForeignKey(
            'fk-checklist_item-checklist_id',
            'checklist_item',
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
            'fk-checklist_item-checklist_id',
            'checklist_item'
        );

        $this->dropIndex(
            'idx-checklist_item-checklist_id',
            'checklist_item'
        );

        $this->dropTable('checklist_item');
    }
}
