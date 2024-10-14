<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `iai_fee_column_in_test_session`.
 */
class m181028_101212_drop_iai_fee_column_in_test_session_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->dropColumn('test_session', 'iaiFee');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        return false;
    }
}
