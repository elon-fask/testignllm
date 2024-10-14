<?php

use yii\db\Migration;

class m160613_110804_add_code_remove_constraint extends Migration
{
    public function up()
    {
        $this->execute("
            ALTER TABLE test_session_checklist_items
  DROP FOREIGN KEY fk_test_session_checklist_items_testSessionId;
            
         ");
    }

    public function down()
    {
        echo "m160613_110804_add_code_remove_constraint cannot be reverted.\n";

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
