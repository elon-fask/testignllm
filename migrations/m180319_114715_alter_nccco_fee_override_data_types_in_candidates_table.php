<?php

use yii\db\Migration;

/**
 * Class m180319_114715_alter_nccco_fee_override_data_types_in_candidates_table
 */
class m180319_114715_alter_nccco_fee_override_data_types_in_candidates_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn('candidates', 'written_nccco_fee_override', $this->decimal(65, 2));
        $this->alterColumn('candidates', 'practical_nccco_fee_override', $this->decimal(65, 2));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180319_114715_alter_nccco_fee_override_data_types_in_candidates_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180319_114715_alter_nccco_fee_override_data_types_in_candidates_table cannot be reverted.\n";

        return false;
    }
    */
}
