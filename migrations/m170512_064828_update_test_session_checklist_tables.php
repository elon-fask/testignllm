<?php

use yii\db\Migration;

class m170512_064828_update_test_session_checklist_tables extends Migration
{
    public function up()
    {
        $this->renameTable('test_session_checklist', 'test_session_checklist_template');
        $this->dropColumn('test_session_checklist_template', 'created_at');
        $this->dropTable('test_session_checklist_notes');
        $this->dropTable('test_session_checklist_items');
    }

    public function down()
    {
        echo "m170512_064828_update_test_session_checklist_tables cannot be reverted.\n";

        return false;
    }
}
