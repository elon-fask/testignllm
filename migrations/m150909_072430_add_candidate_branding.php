<?php

use yii\db\Schema;
use yii\db\Migration;

class m150909_072430_add_candidate_branding extends Migration
{
    public function up()
    {
        $this->execute("
            alter table candidates add column branding varchar(50) null default '';
        
         ");
    }

    public function down()
    {
        echo "m150909_072430_add_candidate_branding cannot be reverted.\n";

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
