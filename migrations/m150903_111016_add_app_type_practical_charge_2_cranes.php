<?php

use yii\db\Schema;
use yii\db\Migration;

class m150903_111016_add_app_type_practical_charge_2_cranes extends Migration
{
    public function up()
    {
        $this->execute("
            alter table application_type add column practicalCharge2Crane double null;
            update application_type set practicalCharge2Crane = 70, practicalCharge = 60;
         ");
    }

    public function down()
    {
        echo "m150903_111016_add_app_type_practical_charge_2_cranes cannot be reverted.\n";

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
