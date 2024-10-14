<?php

use yii\db\Migration;

class m161004_112946_add_cranes extends Migration
{
    public function up()
    {
        $this->execute(" CREATE TABLE cranes
			(
			  id int(11) NOT NULL auto_increment,
              model varchar(250) not null,
              manufacturer varchar(250) not null,
              unitNum varchar(250) not null,
              serialNum varchar(250) not null,
              cad int(11) null default 0,
              weightCerts int(11) null default 0,
              loadChart int(11) null default 0,
              manual int(11) null default 0,
              certificate int(11) null default 0,
              certificateExpirateDate varchar(25) null,
              companyOwner varchar(250) null,
              preChecklistId int(11) null,
              postChecklistId int(11) null,
              date_created datetime default null,
			  PRIMARY KEY (id)
			);
            
            
        
			");
    }

    public function down()
    {
        echo "m161004_112946_add_cranes cannot be reverted.\n";

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
