<?php

use yii\db\Migration;

/**
 * Handles adding cco_id to table `candidates`.
 */
class m221208_191006_add_cco_id_column_to_candidates_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('candidates', 'cco_id', $this->string()->after('practice_time_credits'));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->removeColumn('candidates', 'cco_id');
    }
}
