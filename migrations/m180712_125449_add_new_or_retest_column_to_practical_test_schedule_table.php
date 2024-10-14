<?php

use yii\db\Migration;

/**
 * Handles adding new_or_retest to table `practical_test_schedule`.
 */
class m180712_125449_add_new_or_retest_column_to_practical_test_schedule_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('practical_test_schedule', 'new_or_retest', $this->string()->after('type'));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('practical_test_schedule', 'new_or_retest');
    }
}
