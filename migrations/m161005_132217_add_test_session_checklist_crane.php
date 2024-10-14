<?php

use yii\db\Migration;

class m161005_132217_add_test_session_checklist_crane extends Migration
{
    public function up()
    {
        $this->execute(" alter TABLE test_session_checklist_items add column craneId int(11) null;
			");
    }

    public function down()
    {
        echo "m161005_132217_add_test_session_checklist_crane cannot be reverted.\n";

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
