<?php

use yii\db\Schema;
use yii\db\Migration;

class m150929_150026_add_test_session_school_auto_update_texas extends Migration
{
    public function up()
    {
        $this->execute("
            update test_session set school = 'ACS' where test_site_id in (select id from test_site where name = 'NLC Texas');
        
         ");
    }

    public function down()
    {
        echo "m150929_150026_add_test_session_school_auto_update_texas cannot be reverted.\n";

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
