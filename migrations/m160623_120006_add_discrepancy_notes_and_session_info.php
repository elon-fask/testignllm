<?php

use yii\db\Migration;

class m160623_120006_add_discrepancy_notes_and_session_info extends Migration
{
    public function up()
    {
        $this->execute("
	    	alter table test_site_checklist_item_discrepancy add column testSessionId int(11) null;
            alter table test_site_checklist_item_discrepancy add column notes text null;
			");
    }

    public function down()
    {
        echo "m160623_120006_add_discrepancy_notes_and_session_info cannot be reverted.\n";

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
