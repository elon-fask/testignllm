<?php

use yii\db\Schema;
use yii\db\Migration;

class m150825_070041_add_candidate_session_referral_code extends Migration
{
    public function up()
    {
        $this->execute("
            alter table candidates add column referralCode varchar(250) null default '';
            alter table candidates add column referralPaid smallint default 0;
         ");
    }

    public function down()
    {
        echo "m150825_070041_add_candidate_session_referral_code cannot be reverted.\n";

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
