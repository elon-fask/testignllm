<?php

use yii\db\Schema;
use yii\db\Migration;

class m150817_111244_add_message_inbox_tables extends Migration
{
    public function up()
    {
        $this->execute("
			DROP TABLE IF EXISTS messages;
	    	CREATE TABLE messages
			(
			  id int(11) NOT NULL auto_increment,
			  sender_id varchar(256) NOT NULL,
			  receiver_id varchar(256) NOT NULL,
			  subject varchar(256) NOT NULL DEFAULT '',
			  body text,
			  is_read varchar(20) NOT NULL DEFAULT '0',
			  deleted_by varchar(20) DEFAULT NULL,
		      deleted_at datetime NULL,
			  created_at datetime NULL,
			  CONSTRAINT pk_messages_id PRIMARY KEY (id)
			);
            alter table messages add sender_delete smallint default 0;
	        alter table messages add sender_permanent_delete smallint default 0;
	        alter table messages add receiver_delete smallint default 0;
	        alter table messages add receiver_permanent_delete smallint default 0;			
	    ");
    }

    public function down()
    {
        echo "m150817_111244_add_message_inbox_tables cannot be reverted.\n";

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
