<?php

use yii\db\Migration;

class m160914_113535_add_receipts extends Migration
{
    public function up()
    {
        $this->execute("
	    	CREATE TABLE test_session_receipts
			(
			  id int(11) NOT NULL auto_increment,
              testSessionId int(11) not null,
              filename varchar(2500) not null,
              vendorName varchar(250) not null,
              amount double not null,
              description varchar(2500) not null,
              savedBy int(11) null,
		      date_created datetime default null,
			  PRIMARY KEY (id)
			);
			");
    }

    public function down()
    {
        echo "m160914_113535_add_receipts cannot be reverted.\n";

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
