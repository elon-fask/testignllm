<?php

use yii\db\Schema;
use yii\db\Migration;

class m150812_120455_add_candidate_session_table extends Migration
{
    public function up()
    {
        $this->execute("
         CREATE TABLE candidate_session
         (
             id int(11) NOT NULL auto_increment,
             candidate_id int(11) NOT NULL,
             test_session_id int(11) NOT NULL,
             application_type_id int(11) NOT NULL,
             promoCode varchar(200) null,
             transactionId varchar(200) null,
             amount double null,
             date_created datetime default null,
             date_updated datetime default null,
             PRIMARY KEY (id),
                CONSTRAINT fk_candidate_session_test_session_id FOREIGN KEY (test_session_id)
    			      REFERENCES test_session (id) MATCH SIMPLE
    			      ON UPDATE CASCADE ON DELETE CASCADE,
                CONSTRAINT fk_candidate_session_candidate_id FOREIGN KEY (candidate_id)
    			      REFERENCES candidates (id) MATCH SIMPLE
    			      ON UPDATE CASCADE ON DELETE CASCADE,
                CONSTRAINT fk_candidate_session_application_type_id FOREIGN KEY (application_type_id)
    			      REFERENCES application_type (id) MATCH SIMPLE
    			      ON UPDATE CASCADE ON DELETE CASCADE
         );
        
         ");
    }

    public function down()
    {
        echo "m150812_120455_add_candidate_session_table cannot be reverted.\n";

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
