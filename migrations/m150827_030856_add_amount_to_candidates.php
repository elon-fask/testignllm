<?php

use yii\db\Schema;
use yii\db\Migration;

class m150827_030856_add_amount_to_candidates extends Migration
{
    public function up()
    {
        $this->execute("
            alter table candidate_session drop column remainingAmount;
            alter table candidate_session drop column amount;
            alter table candidate_session drop column promoCode;
            
            alter table candidate_session drop FOREIGN KEY fk_candidate_session_application_type_id;
            alter table candidate_session drop column application_type_id;
            
            alter table candidates add column amount double null default 0;
            alter table candidates add column remainingAmount double null default 0;
            alter table candidates add column application_type_id int(11) NOT NULL;
            alter table test_session add column practical_test_session_id int (11) null;
            drop table candidate_session_transactions;
            CREATE TABLE candidate_transactions
			(
			  id int(11) NOT NULL auto_increment,
              candidateId int(11) not null,
              amount double not null,
              transactionId varchar(200) null,
		      date_created datetime default null,
			  PRIMARY KEY (id)
			);
         ");               
    }

    public function down()
    {
        echo "m150827_030856_add_amount_to_candidates cannot be reverted.\n";

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
