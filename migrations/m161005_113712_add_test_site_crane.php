<?php

use yii\db\Migration;

class m161005_113712_add_test_site_crane extends Migration
{
    public function up()
    {
        $this->execute(" alter TABLE cranes add column testSiteId int(11) null;
			");
    }

    public function down()
    {
        echo "m161005_113712_add_test_site_crane cannot be reverted.\n";

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
