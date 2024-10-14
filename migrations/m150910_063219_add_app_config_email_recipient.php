<?php

use yii\db\Schema;
use yii\db\Migration;

class m150910_063219_add_app_config_email_recipient extends Migration
{
    public function up()
    {

        $this->execute("
            insert into app_config (code, name, val) values ('UNSIGNED_EMAIL_RECIPIENT', 'Notification Email Recipient List', 'pass@californiacraneschool.com,an@californiacraneschool.com,jcarroll@tabletbasedtesting.com');
        
         ");
        
    }

    public function down()
    {
        echo "m150910_063219_add_app_config_email_recipient cannot be reverted.\n";

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
