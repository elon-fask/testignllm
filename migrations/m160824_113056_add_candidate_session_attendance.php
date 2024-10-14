<?php

use yii\db\Migration;

class m160824_113056_add_candidate_session_attendance extends Migration
{
    public function up()
    {
        $this->execute("
	    	CREATE TABLE candidate_session_attendance
			(
			  id int(11) NOT NULL auto_increment,
              candidateId int(11) not null,
              testSessionId int(11) not null,
              dateString varchar(25) not null,
              status int(11) null,
              savedBy int(11) null,
		      date_created datetime default null,
			  PRIMARY KEY (id)
			);
			");
    }

    public function down()
    {
        echo "m160824_113056_add_candidate_session_attendance cannot be reverted.\n";

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
