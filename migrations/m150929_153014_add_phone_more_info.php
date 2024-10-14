<?php

use yii\db\Schema;
use yii\db\Migration;

class m150929_153014_add_phone_more_info extends Migration
{
    public function up()
    {
        $this->execute("
            alter table phone_information add column ad_online_info varchar(250) null;
            alter table phone_information add column friend_email varchar(250) null;
            
         ");
        
    }

    public function down()
    {
        echo "m150929_153014_add_phone_more_info cannot be reverted.\n";

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
