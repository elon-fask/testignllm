<?php

use yii\db\Schema;
use yii\db\Migration;

class m150904_111535_add_app_config extends Migration
{
    public function up()
    {
        $this->execute("
            alter table application_type drop column practicalCharge2Crane;
            alter table application_type drop column practicalCharge;
            alter table application_type drop column iaiLessThan12;
            alter table application_type drop column iaiLessThan15;

            
            
            CREATE TABLE app_config
			(
			  id int(11) NOT NULL auto_increment,
              code varchar(255) not null,
        	  name varchar(255) not null,
              val varchar(255) not null,          
		      date_created datetime default null,
			  date_updated datetime default null,
			  PRIMARY KEY (id)
			);
            insert into app_config (code, name, val) values ('IAI_1_PRACTICAL_CRANE', 'IAI Fee For 1 Practical Crane', '60');
            insert into app_config (code, name, val) values ('IAI_2_PRACTICAL_CRANE', 'IAI Fee For 2 Practical Crane', '70');
            insert into app_config (code, name, val) values ('IAI_FEE_LESS_12', 'IAI Fee (1-12 Student)', '300');
            insert into app_config (code, name, val) values ('IAI_FEE_LESS_15', 'IAI Fee (13-15 Student)', '200');
         ");
    }

    public function down()
    {
        echo "m150904_111535_add_app_config cannot be reverted.\n";

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
