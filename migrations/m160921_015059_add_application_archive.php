<?php

use yii\db\Migration;

class m160921_015059_add_application_archive extends Migration
{
    public function up()
    {
        $this->execute("
            alter table candidates add column isArchived int(11) null default 0;
         ");
    }

    public function down()
    {
        echo "m160921_015059_add_application_archive cannot be reverted.\n";

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
