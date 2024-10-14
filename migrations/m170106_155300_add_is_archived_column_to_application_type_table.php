<?php

use yii\db\Migration;

class m170106_155300_add_is_archived_column_to_application_type_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('application_type', 'isArchived', $this->boolean()->defaultValue(false));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('application_type', 'isArchived');
    }
}
