<?php

use yii\db\Schema;
use yii\db\Migration;

class m160321_141314_add_admin_notification_config extends Migration
{
    public function up()
    {
        $this->execute("
            insert into app_config (code, name, val) values ('ADMIN_NEW_CANDIDATES_EMAIL_RECIPIENT', 'Notification Email Recipient List for new candidates', '');
        
         ");
    }

    public function down()
    {
        echo "m160321_141314_add_admin_notification_config cannot be reverted.\n";

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
