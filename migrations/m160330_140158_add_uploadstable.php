<?php

use yii\db\Schema;
use yii\db\Migration;

class m160330_140158_add_uploadstable extends Migration
{
    public function up()
    {
        $this->execute("
            CREATE TABLE uploads
			(
			  id int(11) NOT NULL auto_increment,
              name varchar(50) null,
              description varchar(800) null,
              isDeleted int(11) null default 0,
              uploaded_by int(11) not null,
		      date_created datetime default null,
			  PRIMARY KEY (id)
			);
         ");
    }

    public function down()
    {
        echo "m160330_140158_add_uploadstable cannot be reverted.\n";

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
