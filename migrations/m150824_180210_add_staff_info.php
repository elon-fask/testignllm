<?php

use yii\db\Schema;
use yii\db\Migration;

class m150824_180210_add_staff_info extends Migration
{
    public function up()
    {
    	$this->execute("
            alter table staff add column fax varchar(250) null default '';
    		alter table staff add column email varchar(250) null default '';
         ");
    }

    public function down()
    {
        echo "m150824_180210_add_staff_info cannot be reverted.\n";

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
