<?php

use yii\db\Migration;

/**
 * Handles adding check_number to table `candidate_transactions`.
 */
class m180111_132909_add_check_number_column_to_candidate_transactions_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('candidate_transactions', 'check_number', $this->string());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('candidate_transactions', 'check_number');
    }
}
