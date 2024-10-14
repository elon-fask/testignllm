<?php

use yii\db\Schema;
use yii\db\Migration;

class m160517_110554_add_program_type extends Migration
{
    public function up()
    {
        $this->execute("
            alter table application_type add column app_type int(11) default 0;
            update application_type set app_type = 1;
         ");
    }

    public function down()
    {
        echo "m160517_110554_add_program_type cannot be reverted.\n";

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
