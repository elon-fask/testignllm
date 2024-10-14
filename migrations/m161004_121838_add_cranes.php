<?php

use yii\db\Migration;

class m161004_121838_add_cranes extends Migration
{
    public function up()
    {
        $this->execute(" 
            alter TABLE cranes add column cadFilename varchar(250) null default '';
            alter TABLE cranes add column weightCertsFilename varchar(250) null default '';
            alter TABLE cranes add column loadChartFilename varchar(250) null default '';
            alter TABLE cranes add column manualFilename varchar(250) null default '';
            alter TABLE cranes add column certificateFilename varchar(250) null default '';
            
			");
    }

    public function down()
    {
        echo "m161004_121838_add_cranes cannot be reverted.\n";

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
