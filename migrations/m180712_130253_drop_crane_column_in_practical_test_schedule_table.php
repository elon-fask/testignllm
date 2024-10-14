<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `crane_column_in_practical_test_schedule`.
 */
class m180712_130253_drop_crane_column_in_practical_test_schedule_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->dropColumn('practical_test_schedule', 'crane');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        echo "m180712_130253_drop_crane_column_in_practical_test_schedule_table cannot be reverted.\n";

        return false;
    }
}
