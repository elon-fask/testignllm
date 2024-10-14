<?php

use yii\db\Schema;
use yii\db\Migration;

class m150831_063205_add_is_candidate_po extends Migration
{
    public function up()
    {
        $this->execute("
            alter table candidates add isPurchaseOrder int(11) not null;
         ");
    }

    public function down()
    {
        echo "m150831_063205_add_is_candidate_po cannot be reverted.\n";

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
