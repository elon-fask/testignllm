<?php

use yii\db\Schema;
use yii\db\Migration;

class m150813_142031_add_user_registration_flag extends Migration
{
    public function up()
    {
        $this->execute("
         alter table candidates add column registration_step int(11) null default 0;  
         ");
    }

    public function down()
    {
        echo "m150813_142031_add_user_registration_flag cannot be reverted.\n";

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
