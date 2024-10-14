<?php

use yii\db\Migration;

class m160606_114110_add_test_site_site_manager extends Migration
{
    public function up()
    {
        $this->execute("     
         alter table test_site add column siteManagerId int(11) null;
         ");
    }

    public function down()
    {
        echo "m160606_114110_add_test_site_site_manager cannot be reverted.\n";

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
