<?php

use yii\db\Migration;

class m160803_125955_add_candidates_written_crane extends Migration
{
    public function up()
    {
        $this->execute("
            alter table candidates add column writtenNumberOfCranes int(11) default 0;
            alter table candidates add column practicalNumberOfCranes int(11) default 0;
            
         ");
    }

    public function down()
    {
        echo "m160803_125955_add_candidates_written_crane cannot be reverted.\n";

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
