<?php

use yii\db\Schema;
use yii\db\Migration;

class m160425_125716_add_previous_session_cranes extends Migration
{
    public function up()
    {
        $this->execute("
            alter table candidate_previous_session add column craneStatus text default null;
         ");
    }

    public function down()
    {
        echo "m160425_125716_add_previous_session_cranes cannot be reverted.\n";

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
