<?php

use yii\db\Schema;
use yii\db\Migration;

class m150817_130259_add_resources_table extends Migration
{
    public function up()
    {
        $this->execute("
			DROP TABLE IF EXISTS resources;
	    	CREATE TABLE resources
			(
			  id int(11) NOT NULL auto_increment,
			  type smallint not null,
			  name varchar(256) NOT NULL,
			  notes varchar(2500) NULL,			  
			  created_at datetime NULL,
			  CONSTRAINT pk_resources_id PRIMARY KEY (id)
			);
	    ");
    }

    public function down()
    {
        echo "m150817_130259_add_resources_table cannot be reverted.\n";

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
