<?php

use yii\db\Migration;

class m161004_115059_update_cranes extends Migration
{
    public function up()
    {
        $this->execute(" alter TABLE cranes add column isDeleted int(11) null default 0;
			");
    }

    public function down()
    {
        echo "m161004_115059_update_cranes cannot be reverted.\n";

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
