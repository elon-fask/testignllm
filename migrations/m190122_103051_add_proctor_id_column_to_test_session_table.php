<?php

use yii\db\Migration;

/**
 * Handles adding proctor_id to table `test_session`.
 */
class m190122_103051_add_proctor_id_column_to_test_session_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('test_session', 'proctor_id', $this->integer()->after('instructor_id'));

        $this->addForeignKey(
            'fk-test_session-proctor_id',
            'test_session',
            'proctor_id',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey(
            'fk-test_session-proctor_id',
            'test_session'
        );

        $this->removeColumn('test_session', 'proctor_id');
    }
}
