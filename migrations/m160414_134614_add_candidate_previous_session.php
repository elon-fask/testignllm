<?php

use yii\db\Schema;
use yii\db\Migration;

class m160414_134614_add_candidate_previous_session extends Migration
{
    public function up()
    {
        $this->execute("
         CREATE TABLE candidate_previous_session
         (
             id int(11) NOT NULL auto_increment,
             candidate_id int(11) NOT NULL,
             test_session_id int(11) NOT NULL,   
             isPass int(11) null default 0,          
             date_created datetime default null,
             PRIMARY KEY (id),
                CONSTRAINT fk_candidate_previous_session_test_session_id FOREIGN KEY (test_session_id)
    			      REFERENCES test_session (id) MATCH SIMPLE
    			      ON UPDATE CASCADE ON DELETE CASCADE,
                CONSTRAINT fk_candidate_previous_session_candidate_id FOREIGN KEY (candidate_id)
    			      REFERENCES candidates (id) MATCH SIMPLE
    			      ON UPDATE CASCADE ON DELETE CASCADE
         );
        
         ");
    }

    public function down()
    {
        echo "m160414_134614_add_candidate_previous_session cannot be reverted.\n";

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
