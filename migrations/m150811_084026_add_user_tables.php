<?php

use yii\db\Schema;
use yii\db\Migration;

class m150811_084026_add_user_tables extends Migration
{
    public function up()
    {
        
         $this->execute("DROP TABLE IF EXISTS user;
         CREATE TABLE user
         (
         id int(11) NOT NULL auto_increment,
         first_name varchar(250) NOT NULL,
         last_name varchar(250) NOT NULL,
         username varchar(250) NOT NULL,
         password varchar(250) NOT NULL,
         role smallint not null default 0,
        
         homePhone varchar(250) null,
         cellPhone varchar(250) null,
         workPhone varchar(250) null,
        
         city varchar(250) null,
         state varchar(250) null,
         zip varchar(250) null,
         address1 varchar(250) null,
         photo smallint default 0,
        
         active smallint default 0,
        
         date_created datetime default null,
         date_updated datetime default null,
         PRIMARY KEY (id)
         );
         insert into user (first_name, last_name, username, password, role, active) values ('root','root', 'root', md5('password'), 1,  1);
        
         ");
        
    }

    public function down()
    {
        echo "m150811_084026_add_user_tables cannot be reverted.\n";

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
