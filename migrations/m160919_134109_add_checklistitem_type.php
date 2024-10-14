<?php

use yii\db\Migration;

class m160919_134109_add_checklistitem_type extends Migration
{
    public function up()
    {
        $this->execute("
            alter table checklist_items add column itemType int(11) null;");
    }

    public function down()
    {
        echo "m160919_134109_add_checklistitem_type cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
