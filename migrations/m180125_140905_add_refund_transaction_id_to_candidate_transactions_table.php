<?php

use yii\db\Migration;

/**
 * Class m180125_140905_add_refund_transaction_id_to_candidate_transactions_table
 */
class m180125_140905_add_refund_transaction_id_to_candidate_transactions_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        {
            $this->addColumn('candidate_transactions', 'transaction_ref_id', $this->integer()->after('chargeType'));
            $this->addForeignKey(
                'fk-candidate_transactions-transaction_ref_id',
                'candidate_transactions',
                'transaction_ref_id',
                'candidate_transactions',
                'id'
            );
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('candidate_transactions', 'transaction_ref_id');
    }
}
