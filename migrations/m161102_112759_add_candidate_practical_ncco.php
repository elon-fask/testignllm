<?php

use yii\db\Migration;

class m161102_112759_add_candidate_practical_ncco extends Migration
{
    public function up()
    {
        $this->execute("
            alter table candidates add column isPracticalNCCCOPaid int(11) null default 0;
         ");
    }

    public function down()
    {
        echo "m161102_112759_add_candidate_practical_ncco cannot be reverted.\n";

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
