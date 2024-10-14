<?php

use yii\db\Migration;

class m160927_113252_add_candidate_remove_session extends Migration
{
    public function up()
    {
        $this->execute("
            alter table candidates add column isWrittenCancelled int(11) null default 0;
            alter table candidates add column isPracticalCancelled int(11) null default 0;
         ");
    }

    public function down()
    {
        echo "m160927_113252_add_candidate_remove_session cannot be reverted.\n";

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
