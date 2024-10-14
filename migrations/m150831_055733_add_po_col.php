<?php

use yii\db\Schema;
use yii\db\Migration;

class m150831_055733_add_po_col extends Migration
{
    public function up()
    {
        $this->execute("
            alter table promo_codes add isPurchaseOrder int(11) not null;
         ");
    }

    public function down()
    {
        echo "m150831_055733_add_po_col cannot be reverted.\n";

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
