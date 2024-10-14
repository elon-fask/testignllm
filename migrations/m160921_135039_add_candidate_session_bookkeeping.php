<?php

use yii\db\Migration;

class m160921_135039_add_candidate_session_bookkeeping extends Migration
{
    public function up()
    {
        $this->execute("
	    	CREATE TABLE candidate_session_bookkeeping
			(
			  id int(11) NOT NULL auto_increment,
              candidateId int(11) not null,
              testSessionId int(11) not null,
              isNCCCOPaid int(11) null default 0,
              hasExcuseLetter int(11) null default 0,
		      date_created datetime default null,
			  PRIMARY KEY (id)
			);
			");
    }

    public function down()
    {
        echo "m160921_135039_add_candidate_session_bookkeeping cannot be reverted.\n";

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
