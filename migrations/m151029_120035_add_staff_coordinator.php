<?php

use yii\db\Schema;
use yii\db\Migration;

class m151029_120035_add_staff_coordinator extends Migration
{
    public function up()
    {
        $this->execute("
            alter table test_session add column test_coordinator_id int(11) null default 0;
         ");
    }

    public function down()
    {
        echo "m151029_120035_add_staff_coordinator cannot be reverted.\n";

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
