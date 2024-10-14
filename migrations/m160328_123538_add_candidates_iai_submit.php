<?php

use yii\db\Schema;
use yii\db\Migration;

class m160328_123538_add_candidates_iai_submit extends Migration
{
    public function up()
    {
        $this->execute("
            alter table candidates add column iaiSubmittedDate datetime null;
         ");
    }

    public function down()
    {
        echo "m160328_123538_add_candidates_iai_submit cannot be reverted.\n";

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
