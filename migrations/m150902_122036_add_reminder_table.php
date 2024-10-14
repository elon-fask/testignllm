<?php

use yii\db\Schema;
use yii\db\Migration;

class m150902_122036_add_reminder_table extends Migration
{
    public function up()
    {
        $this->execute("
            CREATE TABLE reminders
			(
			  id int(11) NOT NULL auto_increment,
        	  note varchar(2500) null,
              remindDate  datetime NOT NULL,
              isComplete int(11) NOT NULL default 0,
		      date_created datetime default null,			  
			  PRIMARY KEY (id)
			);
         ");
        
    }

    public function down()
    {
        echo "m150902_122036_add_reminder_table cannot be reverted.\n";

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
