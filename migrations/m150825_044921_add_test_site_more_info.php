<?php

use yii\db\Schema;
use yii\db\Migration;

class m150825_044921_add_test_site_more_info extends Migration
{
    public function up()
    {
        $this->execute("
            alter table test_site add column name varchar(250) not null default '';
         ");
    }

    public function down()
    {
        echo "m150825_044921_add_test_site_more_info cannot be reverted.\n";

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
