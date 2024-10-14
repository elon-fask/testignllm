<?php

use yii\db\Migration;

class m170119_190535_add_nccco_fee_notes_to_test_session_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('test_session', 'nccco_fee_notes', $this->text());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('test_session', 'nccco_fee_notes');
    }
}
