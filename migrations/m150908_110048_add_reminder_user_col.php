<?php

use yii\db\Schema;
use yii\db\Migration;

class m150908_110048_add_reminder_user_col extends Migration
{
    public function up()
    {
        $this->execute("
            alter table reminders add column userId int(11) not null;
        
         ");
    }

    public function down()
    {
        echo "m150908_110048_add_reminder_user_col cannot be reverted.\n";

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
