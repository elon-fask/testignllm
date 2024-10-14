<?php

use yii\db\Migration;

/**
 * Handles adding starting_location to table `travel_form`.
 */
class m180301_141648_add_starting_location_column_to_travel_form_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('travel_form', 'starting_location', $this->string()->after('one_way'));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
    }
}
