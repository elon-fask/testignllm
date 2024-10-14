<?php

use yii\db\Schema;
use yii\db\Migration;

class m150909_071135_add_staff_archived extends Migration
{
    public function up()
    {
        $this->execute("
            alter table staff add column archived smallint null default 0;
        
         ");
    }

    public function down()
    {
        echo "m150909_071135_add_staff_archived cannot be reverted.\n";

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
