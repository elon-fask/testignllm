<?php

use yii\db\Schema;
use yii\db\Migration;

class m150915_143824_add_candidate_transaction_note extends Migration
{
    public function up()
    {
        $this->execute("
            alter table candidate_transactions add column remarks varchar(1500) null;
        
         ");
    }

    public function down()
    {
        echo "m150915_143824_add_candidate_transaction_note cannot be reverted.\n";

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
