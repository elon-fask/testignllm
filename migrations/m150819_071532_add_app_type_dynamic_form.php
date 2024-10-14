<?php

use yii\db\Schema;
use yii\db\Migration;

class m150819_071532_add_app_type_dynamic_form extends Migration
{
    public function up()
    {
    	$this->execute("
			DROP TABLE IF EXISTS application_type_form_setup;
	    	CREATE TABLE application_type_form_setup
			(
			  id int(11) NOT NULL auto_increment,
    	      application_type_id int (11) not null,
    		  form_name varchar(256) not null,	
			  form_setup text null,
			  created_at datetime NULL,
			  CONSTRAINT pk_application_type_form_setup_id PRIMARY KEY (id),
    		  CONSTRAINT fk_application_type_form_setup_application_type_id FOREIGN KEY (application_type_id)
			      REFERENCES application_type (id) MATCH SIMPLE
			      ON UPDATE CASCADE ON DELETE CASCADE		
			);
	    ");
    }

    public function down()
    {
        echo "m150819_071532_add_app_type_dynamic_form cannot be reverted.\n";

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
