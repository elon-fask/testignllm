<?php

use yii\db\Schema;
use yii\db\Migration;

class m160517_135911_add_session_iai_fee extends Migration
{
    public function up()
    {
        $this->execute("
            alter table test_session add column iaiFee double DEFAULT 0;
         ");
    }

    public function down()
    {
        echo "m160517_135911_add_session_iai_fee cannot be reverted.\n";

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
