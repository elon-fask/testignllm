<?php

use yii\db\Schema;
use yii\db\Migration;

class m150915_114521_add_phone_referrals extends Migration
{
    public function up()
    {
        $this->execute("
            CREATE TABLE phone_information
			(
			  id int(11) NOT NULL auto_increment,
        	  name varchar(250) not null,
              email varchar(250) not null,
              phone varchar(250) not null,
              referral varchar(250) not null,
              referralOther varchar(800) null,
              userId int(11) not null,
              isComplete int(11) NOT NULL default 0,
		      date_created datetime default null,
			  PRIMARY KEY (id)
			);
         ");
    }

    public function down()
    {
        echo "m150915_114521_add_phone_referrals cannot be reverted.\n";

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
