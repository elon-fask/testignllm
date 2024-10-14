<?php

use yii\db\Migration;

/**
 * Handles adding auth_code to table `candidate_transactions`.
 */
class m180831_164155_add_auth_code_column_to_candidate_transactions_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('candidate_transactions', 'auth_code', $this->string()->after('transactionId'));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('candidate_transactions', 'auth_code');
    }
}
