<?php

use yii\db\Schema;
use yii\db\Migration;

class m150811_151836_add_sites_table extends Migration
{
    public function up()
    {
    	$this->execute("DROP TABLE IF EXISTS test_site;
         CREATE TABLE test_site
         (
	         id int(11) NOT NULL auto_increment,
	    	 type smallint NOT NULL,		
	    	 enrollmentType varchar(50) NOT NULL,
	    	 scheduleType varchar(50) NOT NULL,
	
	    	 address varchar(250) NOT NULL,
	    	 city varchar(50) NOT NULL,
	    	 state varchar(50) NOT NULL,		
	    	 zip varchar(50) NOT NULL,
	
	    	 siteNumber varchar(50) NOT NULL,
	    			
	    	 phone varchar(50) NULL,
	    	 fax varchar(50) NULL,
			 email varchar(50) NULL,
	    	 remark varchar(2500) NULL,
	    	    		    	
	         date_created datetime default null,
	         date_updated datetime default null,
	         PRIMARY KEY (id)
         );
    			
    	 CREATE TABLE test_site_service
         (
	         id int(11) NOT NULL auto_increment,
	    	 test_site_id int(11) not null,
	    	 application_type_id int(11) not null,   		    	
	         date_created datetime default null,
	         date_updated datetime default null,
	         PRIMARY KEY (id),
    		 CONSTRAINT fk_test_site_service_test_site_id FOREIGN KEY (test_site_id)
			      REFERENCES test_site (id) MATCH SIMPLE
			      ON UPDATE CASCADE ON DELETE CASCADE,	
    		 CONSTRAINT fk_test_site_service_application_type_id FOREIGN KEY (application_type_id)
			      REFERENCES application_type (id) MATCH SIMPLE
			      ON UPDATE CASCADE ON DELETE CASCADE
         );
    	
         ");
    }

    public function down()
    {
        echo "m150811_151836_add_sites_table cannot be reverted.\n";

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
