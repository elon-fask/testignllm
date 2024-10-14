<?php

use yii\db\Migration;

/**
 * Class m180716_115737_add_practice_hours_column_in_practical_test_schedule_table
 */
class m180716_115737_add_practice_hours_column_in_practical_test_schedule_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('practical_test_schedule', 'practice_hours', $this->decimal(65, 2)->after('time'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('practical_test_schedule', 'practice_hours');
    }
}
