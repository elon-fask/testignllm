<?php

use yii\db\Migration;
use app\models\CandidateTransactions;

class m160601_121230_add_charge_type extends Migration
{
    public function up()
    {
         $this->execute("
            alter table candidate_transactions add column chargeType int(11) null;
         ");
        
        $this->execute("
            update candidate_transactions set chargeType = ".CandidateTransactions::SUBTYPE_OTHERS." where paymentType = ".CandidateTransactions::TYPE_STUDENT_CHARGE.";
         ");
    }

    public function down()
    {
        echo "m160601_121230_add_charge_type cannot be reverted.\n";

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
