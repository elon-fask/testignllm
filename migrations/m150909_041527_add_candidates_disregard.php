<?php

use yii\db\Schema;
use yii\db\Migration;

class m150909_041527_add_candidates_disregard extends Migration
{
    public function up()
    {
        $this->execute("
            alter table candidates add column disregard smallint null default 0;
        
         ");
    }

    public function down()
    {
        echo "m150909_041527_add_candidates_disregard cannot be reverted.\n";

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
