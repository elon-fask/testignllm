<?php

use yii\db\Migration;

class m170512_062444_rename_checklist_tables_to_checklist_template extends Migration
{
    public function up()
    {
        $this->renameTable('checklist', 'checklist_template');
        $this->renameTable('checklist_items', 'checklist_item_template');
    }

    public function down()
    {
        $this->renameTable('checklist_template', 'checklist');
        $this->renameTable('checklist_item_template', 'checklist_items');
    }
}
