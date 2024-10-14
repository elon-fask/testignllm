<?php

use yii\db\Schema;
use yii\db\Migration;

class m160420_131925_add_previous_more_info extends Migration
{
    public function up()
    {
        $this->execute("
            alter table candidate_previous_session add column isConfirmed int(11) default 0;
            alter table candidate_previous_session add column fileLocation varchar(250) default '';
         ");
    }

    public function down()
    {
        echo "m160420_131925_add_previous_more_info cannot be reverted.\n";

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
