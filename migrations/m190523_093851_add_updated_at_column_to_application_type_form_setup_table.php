<?php

use yii\db\Migration;

/**
 * Handles adding updated_at to table `application_type_form_setup`.
 */
class m190523_093851_add_updated_at_column_to_application_type_form_setup_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('application_type_form_setup', 'updated_at', $this->dateTime()->after('created_at'));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->removeColumn('application_type_form_setup', 'updated_at');
    }
}
