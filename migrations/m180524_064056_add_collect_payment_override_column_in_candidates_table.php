<?php

use yii\db\Migration;

/**
 * Class m180524_064056_add_collect_payment_override_column_in_candidates_table
 */
class m180524_064056_add_collect_payment_override_column_in_candidates_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('candidates', 'collect_payment_override', $this->boolean()->defaultValue(false)->after('purchase_order_number'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180524_064056_add_collect_payment_override_column_in_candidates_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180524_064056_add_collect_payment_override_column_in_candidates_table cannot be reverted.\n";

        return false;
    }
    */
}
