<?php

use yii\db\Schema;
use yii\db\Migration;

class m150818_155409_add_session_limit extends Migration
{
    public function up()
    {
    	$this->execute("
         alter table test_session add column numOfCandidates int(11) null default 0;
    	 alter table application_type add column infoText text null;
         ");
    }

    public function down()
    {
        echo "m150818_155409_add_session_limit cannot be reverted.\n";

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
