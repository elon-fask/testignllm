<?php

use yii\db\Migration;

/**
 * Handles adding nccco_fee_override to table `candidates`.
 */
class m180227_041153_add_nccco_fee_override_column_to_candidates_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('candidates', 'written_nccco_fee_override', $this->decimal(null, 2)->after('purchase_order_number'));
        $this->addColumn('candidates', 'practical_nccco_fee_override', $this->decimal(null, 2)->after('written_nccco_fee_override'));
        $this->update('candidates', ['written_nccco_fee_override' => 0], ['isWrittenNCCCOPaid' => 1]);
        $this->dropColumn('candidates', 'isWrittenNCCCOPaid');
        $this->dropColumn('candidates', 'isPracticalNCCCOPaid');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
    }
}
