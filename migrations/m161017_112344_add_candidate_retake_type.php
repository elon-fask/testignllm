<?php

use yii\db\Migration;

class m161017_112344_add_candidate_retake_type extends Migration
{
    public function up()
    {
         $this->execute("
         alter table candidates add column isRetake int(11) null default 0;
         alter table candidates add column retakeType int(11) null default 0;
         ");
    }

    public function down()
    {
        echo "m161017_112344_add_candidate_retake_type cannot be reverted.\n";

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
