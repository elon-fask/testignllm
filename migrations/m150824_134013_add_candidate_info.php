<?php

use yii\db\Schema;
use yii\db\Migration;

class m150824_134013_add_candidate_info extends Migration
{
    public function up()
    {
        $this->execute("
            alter table candidates add column suffix varchar(250) null default '';
            alter table candidates add column ccoCertNumber varchar(250) null default '';
            alter table candidates add column ssn1 varchar(50) null default '';
            alter table candidates add column ssn2 varchar(50) null default '';
            alter table candidates add column ssn3 varchar(50) null default '';
            alter table candidates add column birthday varchar(50) null default '';
            alter table candidates add column cellNumber varchar(50) null default '';
            alter table candidates add column faxNumber varchar(50) null default '';
            alter table candidates add column requestAda smallint default 0;
         ");
    }

    public function down()
    {
        echo "m150824_134013_add_candidate_info cannot be reverted.\n";

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
