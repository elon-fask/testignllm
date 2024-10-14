<?php

use yii\db\Schema;
use yii\db\Migration;

class m150903_083420_add_candidate_iai_fee extends Migration
{
    public function up()
    {
        $this->execute("
            alter table candidates add column totalIaiFee double null default 0;
         ");
    }

    public function down()
    {
        echo "m150903_083420_add_candidate_iai_fee cannot be reverted.\n";

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
