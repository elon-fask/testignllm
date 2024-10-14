<?php

use yii\db\Migration;

/**
 * Handles adding notes to table `travel_forms`.
 */
class m180313_143730_add_notes_column_to_travel_forms_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('travel_form', 'notes', $this->string()->after('comment'));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
    }
}
