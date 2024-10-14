<?php

use yii\db\Schema;
use yii\db\Migration;

class m150915_041256_add_student_survey extends Migration
{
    public function up()
    {
        $this->execute("
            alter table candidates add column survey varchar(250) null;
        
         ");
    }

    public function down()
    {
        echo "m150915_041256_add_student_survey cannot be reverted.\n";

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
