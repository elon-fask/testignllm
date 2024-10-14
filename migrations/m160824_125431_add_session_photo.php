<?php

use yii\db\Migration;

class m160824_125431_add_session_photo extends Migration
{
    public function up()
    {
        $this->execute("
	    	CREATE TABLE test_session_photos
			(
			  id int(11) NOT NULL auto_increment,
              testSessionId int(11) not null,
              filename varchar(2500) not null,
              savedBy int(11) null,
		      date_created datetime default null,
			  PRIMARY KEY (id)
			);
			");
    }

    public function down()
    {
        echo "m160824_125431_add_session_photo cannot be reverted.\n";

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
