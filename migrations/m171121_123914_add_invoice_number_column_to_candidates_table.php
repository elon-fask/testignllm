<?php

use yii\db\Migration;

/**
 * Handles adding invoice_number to table `candidates`.
 */
class m171121_123914_add_invoice_number_column_to_candidates_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('candidates', 'invoice_number', $this->string());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('candidates', 'invoice_number');
    }
}
