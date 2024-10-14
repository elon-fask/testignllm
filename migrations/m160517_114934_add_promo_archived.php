<?php

use yii\db\Schema;
use yii\db\Migration;

class m160517_114934_add_promo_archived extends Migration
{
    public function up()
    {
        $this->execute("
            alter table promo_codes add column archived int(11) default 0;
         ");
    }

    public function down()
    {
        echo "m160517_114934_add_promo_archived cannot be reverted.\n";

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
