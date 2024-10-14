<?php

use yii\db\Schema;
use yii\db\Migration;

class m151026_151528_add_session_instructor extends Migration
{
    public function up()
    {
        $this->execute("
            alter table test_session add column instructor_id int(11) null default 0;        
         ");
    }

    public function down()
    {
        echo "m151026_151528_add_session_instructor cannot be reverted.\n";

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
