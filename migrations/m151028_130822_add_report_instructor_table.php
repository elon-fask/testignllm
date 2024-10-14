<?php

use yii\db\Schema;
use yii\db\Migration;

class m151028_130822_add_report_instructor_table extends Migration
{
    public function up()
    {
    	$this->execute("
         CREATE TABLE last_instructor
         (
	         id int(11) NOT NULL auto_increment,
    	     instructor varchar(150) not null,
	    	 date_created datetime default null,
	         PRIMARY KEY (id)
         );
         ");
    }

    public function down()
    {
        echo "m151028_130822_add_report_instructor_table cannot be reverted.\n";

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
