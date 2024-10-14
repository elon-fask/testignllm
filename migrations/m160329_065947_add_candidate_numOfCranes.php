<?php

use yii\db\Schema;
use yii\db\Migration;

class m160329_065947_add_candidate_numOfCranes extends Migration
{
    public function up()
    {
        $this->execute("
            alter table candidates add column numberOfCranes int(11) default 0;
         ");
    }

    public function down()
    {
        echo "m160329_065947_add_candidate_numOfCranes cannot be reverted.\n";

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
