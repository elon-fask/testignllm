<?php

use yii\db\Migration;

class m160928_113352_add_class_schedule extends Migration
{
    public function up()
    {
        $this->execute(" CREATE TABLE test_session_class_schedule
			(
			  id int(11) NOT NULL auto_increment,
              testSessionId int(11) not null,
			  classDate varchar(20) not null,
              startTime varchar(20) not null,
              endTime varchar(20) not null,
              date_created datetime default null,
			  PRIMARY KEY (id)
			);
        
			");
    }

    public function down()
    {
        echo "m160928_113352_add_class_schedule cannot be reverted.\n";

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
