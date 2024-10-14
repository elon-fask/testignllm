<?php

use yii\db\Migration;

/**
 * Handles adding completed to table `travel_form`.
 */
class m180306_124017_add_completed_column_to_travel_form_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('travel_form', 'completed', $this->boolean()->defaultValue(false)->after('id'));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
    }
}
