<?php

use yii\db\Migration;

class m161006_120704_add_post_written_checklist extends Migration
{
    public function up()
    {
        $this->execute("
         alter table test_site add column writtenPostChecklistId int(11) null;
         alter table test_session add column writtenPostChecklistId int(11) null;
         ");
    }

    public function down()
    {
        echo "m161006_120704_add_post_written_checklist cannot be reverted.\n";

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
