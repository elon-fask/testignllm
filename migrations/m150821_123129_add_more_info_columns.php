<?php

use yii\db\Schema;
use yii\db\Migration;

class m150821_123129_add_more_info_columns extends Migration
{
    public function up()
    {
        $this->execute("
         alter table candidates add column has1000Exp smallint default 0;
         ");
    }

    public function down()
    {
        echo "m150821_123129_add_more_info_columns cannot be reverted.\n";

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
