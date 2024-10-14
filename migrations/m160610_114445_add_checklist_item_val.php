<?php

use yii\db\Migration;

class m160610_114445_add_checklist_item_val extends Migration
{
    public function up()
    {
        $this->execute("
         alter table checklist_items add column val int(11) null;
         ");
    }

    public function down()
    {
        echo "m160610_114445_add_checklist_item_val cannot be reverted.\n";

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
