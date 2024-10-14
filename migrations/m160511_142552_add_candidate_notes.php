<?php

use yii\db\Schema;
use yii\db\Migration;

class m160511_142552_add_candidate_notes extends Migration
{
    public function up()
    {
    	$this->execute("
         CREATE TABLE candidate_notes
         (
             id int(11) NOT NULL auto_increment,
             candidate_id int(11) NOT NULL,
    	     user_id int(11) NOT NULL,    
    	     notes text null,         
             date_created datetime default null,
             date_updated datetime default null,
             PRIMARY KEY (id),
                CONSTRAINT fk_candidate_notes_candidate_id FOREIGN KEY (candidate_id)
    			      REFERENCES candidates (id) MATCH SIMPLE
    			      ON UPDATE CASCADE ON DELETE CASCADE,
                CONSTRAINT fk_candidate_notes_user_id FOREIGN KEY (user_id)
    			      REFERENCES user (id) MATCH SIMPLE
    			      ON UPDATE CASCADE ON DELETE CASCADE
         );
    	
         ");
    }

    public function down()
    {
        echo "m160511_142552_add_candidate_notes cannot be reverted.\n";

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
