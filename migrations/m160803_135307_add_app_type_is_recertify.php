<?php

use yii\db\Migration;

class m160803_135307_add_app_type_is_recertify extends Migration
{
    public function up()
    {
        $this->execute("
            alter table application_type add column isRecertify int(11) default 0;
        
         ");
    }

    public function down()
    {
        echo "m160803_135307_add_app_type_is_recertify cannot be reverted.\n";

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
