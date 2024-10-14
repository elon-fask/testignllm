<?php

use yii\db\Migration;

/**
 * Handles adding nccco_test_fees_credit to table `test_session`.
 */
class m180510_092913_add_nccco_test_fees_credit_column_to_test_session_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('test_session', 'nccco_test_fees_credit', $this->decimal(65, 2)->after('nccco_fee_notes'));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
    }
}
