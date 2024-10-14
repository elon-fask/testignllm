<?php

use yii\db\Migration;

class m160930_033733_add_candidate_schedule extends Migration
{
    public function up()
    {
        $this->execute(" CREATE TABLE candidate_test_session_class_schedule
			(
			  id int(11) NOT NULL auto_increment,
              candidateId int(11) not null,
              testSessionClassScheduleId int(11) not null,
              date_created datetime default null,
			  PRIMARY KEY (id)
			);
        
			");
    }

    public function down()
    {
        echo "m160930_033733_add_candidate_schedule cannot be reverted.\n";

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
