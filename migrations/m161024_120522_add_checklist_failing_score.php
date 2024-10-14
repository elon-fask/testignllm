<?php

use yii\db\Migration;

class m161024_120522_add_checklist_failing_score extends Migration
{
    public function up()
    {
        $this->execute("
            alter table checklist_items add column failingScore int(11) null;");
    }

    public function down()
    {
        echo "m161024_120522_add_checklist_failing_score cannot be reverted.\n";

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
