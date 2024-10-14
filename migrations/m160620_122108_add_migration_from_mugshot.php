<?php

use yii\db\Migration;

class m160620_122108_add_migration_from_mugshot extends Migration
{
    public function up()
    {
        $this->execute("
            alter TABLE candidates add column photo smallint default 0;			
			");
        
        $this->execute("
	    	CREATE TABLE candidate_session_ratings
			(
			  id int(11) NOT NULL auto_increment,
              candidateId int(11) not null,
              testSessionId int(11) not null,
              checkin datetime default null,
              checkout datetime default null,
              rating int(11) null,
		      date_created datetime default null,
			  PRIMARY KEY (id)
			);
			");
        /*
        $this->execute("
            alter table candidate_session add test_start_date datetime default null
			");
        */
        
    }

    public function down()
    {
        echo "m160620_122108_add_migration_from_mugshot cannot be reverted.\n";

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
