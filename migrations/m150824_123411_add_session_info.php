<?php

use yii\db\Schema;
use yii\db\Migration;

class m150824_123411_add_session_info extends Migration
{
    public function up()
    {
        $this->execute("
            alter table test_session add column session_number varchar(250) null default '';
            alter table test_session add column staff_id int(11) null;
            CREATE TABLE staff
			(
			  id int(11) NOT NULL auto_increment,
        	  firstName varchar(255) not null,
              lastName varchar(255) not null,
              staffType int(11) not null,                       
		      date_created datetime default null,
			  date_updated datetime default null,
			  PRIMARY KEY (id)
			);
         ");
    }

    public function down()
    {
        echo "m150824_123411_add_session_info cannot be reverted.\n";

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
