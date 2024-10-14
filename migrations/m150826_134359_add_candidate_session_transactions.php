<?php

use yii\db\Schema;
use yii\db\Migration;

class m150826_134359_add_candidate_session_transactions extends Migration
{
    public function up()
    {
        $this->execute("
            alter table candidate_session drop column transactionId;
            alter table candidate_session add column remainingAmount double null default 0;
            CREATE TABLE candidate_session_transactions
			(
			  id int(11) NOT NULL auto_increment,
              candidateSessionId int(11) not null,
              amount double not null,
              transactionId varchar(200) null,
		      date_created datetime default null,
			  PRIMARY KEY (id)
			);
         ");
        
    }

    public function down()
    {
        echo "m150826_134359_add_candidate_session_transactions cannot be reverted.\n";

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
