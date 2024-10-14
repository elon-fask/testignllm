<?php

use yii\db\Migration;

/**
 * Class m180912_065458_remove_amount_columns_in_candidates_table
 */
class m180912_065458_remove_amount_columns_in_candidates_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->dropColumn('candidates', 'amount');
        $this->dropColumn('candidates', 'remainingAmount');
        $this->dropColumn('candidates', 'totalIaiFee');
        $this->dropColumn('candidates', 'referralPaid');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180912_065458_remove_amount_columns_in_candidates_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180912_065458_remove_amount_columns_in_candidates_table cannot be reverted.\n";

        return false;
    }
    */
}
