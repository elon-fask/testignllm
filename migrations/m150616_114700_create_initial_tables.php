<?php

use yii\db\Schema;
use yii\db\Migration;

class m150616_114700_create_initial_tables extends Migration
{
    public function up()
    {
       
        $this->execute("CREATE TABLE application_type
			(
			  id int(11) NOT NULL auto_increment,
        	  name varchar(255) not null,
              keyword varchar(255) not null,
              description varchar(255) null,
              price double DEFAULT 0,
              iaiFee double DEFAULT 0,
              lateFee double DEFAULT 0,
              practicalCharge double DEFAULT 0,           
		      date_created datetime default null,
			  date_updated datetime default null,
			  PRIMARY KEY (id)
			);
            insert into application_type (name, keyword, description, price, iaiFee, lateFee, practicalCharge) values ('New Certificate', 'certify','National Crane Operator Certification', 1795, 175, 0, 70);
            insert into application_type (name, keyword, description, price, iaiFee, lateFee, practicalCharge) values ('Re-certify+1000', 'rcw','Re-certify With 1000 Hours', 995, 155, 0, 0);
            insert into application_type (name, keyword, description, price, iaiFee, lateFee, practicalCharge) values ('Re-certify', 'rcwo','Re-certify Without 1000 Hours', 1595, 155,0,70);
            insert into application_type (name, keyword, description, price, iaiFee, lateFee, practicalCharge) values ('Written Test', 'test',' Written Test Only', 100, 0,0,0);
            insert into application_type (name, keyword, description, price, iaiFee, lateFee, practicalCharge) values ('Written Re-test', 'retest','Written Re-Test', 0, 0,0,0);
            insert into application_type (name, keyword, description, price, iaiFee, lateFee, practicalCharge) values ('Written Class', 'class','Class and Written Test Only', 1195, 0,0,0);
            insert into application_type (name, keyword, description, price, iaiFee, lateFee, practicalCharge) values ('Private Certification', 'private',' Private Certification Classes', 1795, 175, 0,70);
            insert into application_type (name, keyword, description, price, iaiFee, lateFee, practicalCharge) values ('Private Re-Certification', 'rprivate','Private Re-Certification Classes', 1595, 175, 0, 70);
            insert into application_type (name, keyword, description, price, iaiFee, lateFee, practicalCharge) values ('Practical Only', 'practic',' Practical Only', 0, 0,0, 70);
            ");
    }

    public function down()
    {
        echo "m150616_114700_create_initial_tables cannot be reverted.\n";

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
