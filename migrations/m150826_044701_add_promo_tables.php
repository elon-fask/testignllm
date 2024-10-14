<?php

use yii\db\Schema;
use yii\db\Migration;

class m150826_044701_add_promo_tables extends Migration
{
    public function up()
    {
        $this->execute("          
            CREATE TABLE promo_codes
			(
			  id int(11) NOT NULL auto_increment,
        	  code varchar(255) not null,
              discount double not null,
              assignedToName varchar(255) not null,                       
		      date_created datetime default null,
			  date_updated datetime default null,
			  PRIMARY KEY (id)
			);
         ");

    }

    public function down()
    {
        echo "m150826_044701_add_promo_tables cannot be reverted.\n";

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
