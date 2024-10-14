<?php

use yii\db\Migration;

class m160921_115332_add_candidate_is_nccco_paid extends Migration
{
    public function up()
    {
        $this->execute("
            alter table candidates add column isWrittenNCCCOPaid int(11) null default 0;
         ");
    }

    public function down()
    {
        echo "m160921_115332_add_candidate_is_nccco_paid cannot be reverted.\n";

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
