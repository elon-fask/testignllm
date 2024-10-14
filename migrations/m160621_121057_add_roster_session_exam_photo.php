<?php

use yii\db\Migration;

class m160621_121057_add_roster_session_exam_photo extends Migration
{
    public function up()
    {

        $this->execute("
	    	CREATE TABLE candidate_session_exam_photo
			(
			  id int(11) NOT NULL auto_increment,
              candidateId int(11) not null,
              testSessionId int(11) not null,
              isDeleted int(11) null,
              uploadedBy int(11) null,
		      date_created datetime default null,
			  PRIMARY KEY (id)
			);
			");
    }

    public function down()
    {
        echo "m160621_121057_add_roster_session_exam_photo cannot be reverted.\n";

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
