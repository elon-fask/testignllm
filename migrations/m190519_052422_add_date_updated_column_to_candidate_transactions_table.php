<?php

use yii\db\Migration;

/**
 * Handles adding date_updated to table `candidate_transactions`.
 */
class m190519_052422_add_date_updated_column_to_candidate_transactions_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('candidate_transactions', 'date_updated', $this->dateTime()->after('retest_crane_selection'));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->removeColumn('candidate_transactions', 'date_updated');
    }
}
