<?php

use yii\db\Schema;
use yii\db\Migration;

class m150824_130351_add_staff_info extends Migration
{
    public function up()
    {
        $this->execute("
            alter table staff add column phone varchar(250) null default '';            
         ");
    }

    public function down()
    {
        echo "m150824_130351_add_staff_info cannot be reverted.\n";

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
