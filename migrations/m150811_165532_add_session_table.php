<?php

use yii\db\Schema;
use yii\db\Migration;

class m150811_165532_add_session_table extends Migration
{
    public function up()
    {
    	$this->execute("
         CREATE TABLE test_session
         (
	         id int(11) NOT NULL auto_increment,
	    	 test_site_id int(11) NOT NULL,
	    	 enrollmentType varchar(50) NOT NULL,
	    	 start_date datetime NOT NULL,
    		 end_date datetime NOT NULL,
    				    	 	    	   
	         date_created datetime default null,
	         date_updated datetime default null,
	         PRIMARY KEY (id),
    		 CONSTRAINT fk_test_session_test_site_id FOREIGN KEY (test_site_id)
			      REFERENCES test_site (id) MATCH SIMPLE
			      ON UPDATE CASCADE ON DELETE CASCADE
         );
         ");
    }

    public function down()
    {
        echo "m150811_165532_add_session_table cannot be reverted.\n";

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
