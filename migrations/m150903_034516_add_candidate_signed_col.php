<?php

use yii\db\Schema;
use yii\db\Migration;

class m150903_034516_add_candidate_signed_col extends Migration
{
    public function up()
    {
        $this->execute("
            alter table candidates add signedForms text null;
         ");
    }

    public function down()
    {
        echo "m150903_034516_add_candidate_signed_col cannot be reverted.\n";

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
