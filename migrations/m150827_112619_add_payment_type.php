<?php

use yii\db\Schema;
use yii\db\Migration;

class m150827_112619_add_payment_type extends Migration
{
    public function up()
    {
        $this->execute("
            alter table candidate_transactions add column paymentType int(11) not null;
         ");        
    }

    public function down()
    {
        echo "m150827_112619_add_payment_type cannot be reverted.\n";

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
