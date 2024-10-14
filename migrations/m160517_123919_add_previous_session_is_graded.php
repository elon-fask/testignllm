<?php

use yii\db\Schema;
use yii\db\Migration;

class m160517_123919_add_previous_session_is_graded extends Migration
{
    public function up()
    {
        $this->execute("
            alter table candidate_previous_session add column isGraded int(11) default 1;
         ");
    }

    public function down()
    {
        echo "m160517_123919_add_previous_session_is_graded cannot be reverted.\n";

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
