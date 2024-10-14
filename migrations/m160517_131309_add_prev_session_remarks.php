<?php

use yii\db\Schema;
use yii\db\Migration;

class m160517_131309_add_prev_session_remarks extends Migration
{
    public function up()
    {
        $this->execute("
            alter table candidate_previous_session add column remarks text null;
         ");
    }

    public function down()
    {
        echo "m160517_131309_add_prev_session_remarks cannot be reverted.\n";

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
