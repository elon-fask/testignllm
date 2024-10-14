<?php

use yii\db\Migration;

/**
 * Handles adding one_way to table `travel_form`.
 */
class m180223_122647_add_one_way_column_to_travel_form_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
       $this->addColumn('travel_form', 'one_way', $this->boolean()->notNull()->after('name'));
       $this->alterColumn('travel_form', 'return_loc', $this->string()->null()->defaultValue(null));
       $this->alterColumn('travel_form', 'return_date', $this->date()->null()->defaultValue(null));
       $this->alterColumn('travel_form', 'return_time', $this->string()->null()->defaultValue(null));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
    }
}
