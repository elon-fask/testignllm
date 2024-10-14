<?php

use yii\db\Schema;
use yii\db\Migration;

class m150929_125339_add_test_session_school extends Migration
{
    public function up()
    {
        $this->execute("
            alter table test_session add column school varchar(250) not null default '';
        
         ");
        
        $this->execute("
            update test_session set school = 'CCS';
        
         ");
    }

    public function down()
    {
        echo "m150929_125339_add_test_session_school cannot be reverted.\n";

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
