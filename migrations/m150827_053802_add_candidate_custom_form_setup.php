<?php

use yii\db\Schema;
use yii\db\Migration;

class m150827_053802_add_candidate_custom_form_setup extends Migration
{
    public function up()
    {
        $this->execute("
            alter table candidates add column custom_form_setup text null;            
         ");
    }

    public function down()
    {
        echo "m150827_053802_add_candidate_custom_form_setup cannot be reverted.\n";

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
