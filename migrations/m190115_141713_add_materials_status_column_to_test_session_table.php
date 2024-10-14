<?php

use yii\db\Migration;

/**
 * Handles adding materials_status to table `test_session`.
 */
class m190115_141713_add_materials_status_column_to_test_session_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('test_session', 'materials_status', $this->string()->defaultValue('NOT_SENT'));
        $this->addColumn('test_session', 'materials_tracking_no', $this->string());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->removeColumn('test_session', 'materials_tracking_no');
        $this->removeColumn('test_session', 'materials_status');
    }
}
