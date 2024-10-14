<?php

use yii\db\Schema;
use yii\db\Migration;

class m150903_080026_add_app_type_student_iai extends Migration
{
    public function up()
    {
        $this->execute("
            alter table application_type add iaiLessThan12 double null default 300.00;
            alter table application_type add iaiLessThan15 double null default 200.00;
         ");
    }

    public function down()
    {
        echo "m150903_080026_add_app_type_student_iai cannot be reverted.\n";

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
