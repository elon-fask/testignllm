<?php

use yii\db\Schema;
use yii\db\Migration;

class m150915_064947_add_student_survey_other extends Migration
{
    public function up()
    {
        $this->execute("
            alter table candidates add column surveyOther varchar(1500) null;
        
         ");
    }

    public function down()
    {
        echo "m150915_064947_add_student_survey_other cannot be reverted.\n";

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
