<?php

use yii\db\Migration;

/**
 * Handles adding cross_out_cc_fields to table `application_type`.
 */
class m180102_074248_add_cross_out_cc_fields_column_to_application_type_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('application_type', 'cross_out_cc_fields', $this->boolean()->notNull()->defaultValue(1)->after('lateFee'));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('application_type', 'cross_out_cc_fields');
    }
}
