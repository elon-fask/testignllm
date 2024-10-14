<?php

use yii\db\Migration;

/**
 * Handles adding practice_time_credits to table `candidates`.
 */
class m180719_072731_add_practice_time_credits_column_to_candidates_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('candidates', 'practice_time_credits', $this->decimal(65, 2)->after('instructor_notes'));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('candidates', 'practice_time_credits');
    }
}
