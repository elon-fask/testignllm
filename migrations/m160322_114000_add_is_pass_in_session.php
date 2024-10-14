<?php

use yii\db\Schema;
use yii\db\Migration;

class m160322_114000_add_is_pass_in_session extends Migration
{
    public function up()
    {
        //1 passed
        //2 failed
        $this->execute("
            alter table candidate_session add column isPass int(11) null default 0;
         ");
    }

    public function down()
    {
        echo "m160322_114000_add_is_pass_in_session cannot be reverted.\n";

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
