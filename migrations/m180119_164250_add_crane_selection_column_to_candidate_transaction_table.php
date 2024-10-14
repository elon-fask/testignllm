<?php

use yii\db\Migration;

/**
 * Handles adding crane_selection to table `candidate_transaction`.
 */
class m180119_164250_add_crane_selection_column_to_candidate_transaction_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('candidate_transactions', 'retest_crane_selection', $this->string()->after('check_number'));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('candidate_transactions', 'retest_crane_selection');
    }
}
