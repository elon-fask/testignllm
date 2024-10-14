<?php

use yii\db\Schema;
use yii\db\Migration;

class m150812_065806_add_candidates_table extends Migration
{
    public function up()
    {
        $this->execute("DROP TABLE IF EXISTS candidates;
         CREATE TABLE candidates
         (
         id int(11) NOT NULL auto_increment,
         first_name varchar(250) NOT NULL,
         last_name varchar(250) NOT NULL,
         middle_name varchar(250) NULL,
         email varchar(250) NOT NULL,        
         phone varchar(250) NOT NULL,
     
         address varchar(250) null,
         city varchar(250) null,
         state varchar(250) null,
         zip varchar(250) null,
         
         companyName varchar(250) null,
        companyFax varchar(250) null,
        companyPhone varchar(250) null,
        companyAddress varchar(250) null,
        companyCity varchar(250) null,
        companyState varchar(250) null,
        companyZip varchar(250) null,
        contactPerson varchar(250) null,
            
            
         date_created datetime default null,
         date_updated datetime default null,
         PRIMARY KEY (id)
         );
        
         ");
    }

    public function down()
    {
        echo "m150812_065806_add_candidates_table cannot be reverted.\n";

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
