<?php

use yii\db\Migration;

/**
 * Handles adding purchase_order_number to table `candidates`.
 */
class m171121_124327_add_purchase_order_number_column_to_candidates_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('candidates', 'purchase_order_number', $this->string());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('candidates', 'purchase_order_number');
    }
}
