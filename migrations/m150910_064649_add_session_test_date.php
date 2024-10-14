<?php

use yii\db\Schema;
use yii\db\Migration;

class m150910_064649_add_session_test_date extends Migration
{
    public function up()
    {
        $this->execute("
            alter table test_session add column testing_date datetime NULL;
            alter table test_session add column registration_close_date datetime NULL;
        
         ");
    }

    public function down()
    {
        echo "m150910_064649_add_session_test_date cannot be reverted.\n";

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
