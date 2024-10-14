<?php

use yii\db\Schema;
use yii\db\Migration;

class m160322_150425_add_contact_email extends Migration
{
    public function up()
    {
        $this->execute("
            alter table candidates add column contactEmail varchar(50) null;
         ");
    }

    public function down()
    {
        echo "m160322_150425_add_contact_email cannot be reverted.\n";

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
