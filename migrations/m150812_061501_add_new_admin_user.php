<?php

use yii\db\Schema;
use yii\db\Migration;

class m150812_061501_add_new_admin_user extends Migration
{
    public function up()
    {
        $this->execute("
         insert into user (first_name, last_name, username, password, role, active) values ('linda','temp', 'leenda', md5('crane2fly'), 1,  1);
        
         ");
    }

    public function down()
    {
        echo "m150812_061501_add_new_admin_user cannot be reverted.\n";

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
