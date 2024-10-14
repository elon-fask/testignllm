<?php

use yii\db\Migration;

/**
 * Handles the creation of table `practical_test_schedule`.
 */
class m180517_101850_create_practical_test_schedule_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('practical_test_schedule', [
            'id' => $this->primaryKey(),
            'candidate_id' => $this->integer(),
            'test_session_id' => $this->integer()->notNull(),
            'day' => $this->integer()->notNull(),
            'type' => $this->string()->notNull(),
            'crane' => $this->string()->notNull(),
            'time' => $this->string()->notNull()
        ]);

        $this->createIndex(
            'idx-practical_test_schedule-candidate_id',
            'practical_test_schedule',
            'candidate_id'
        );

        $this->addForeignKey(
            'fk-practical_test_schedule-candidate_id',
            'practical_test_schedule',
            'candidate_id',
            'candidates',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-practical_test_schedule-test_session_id',
            'practical_test_schedule',
            'candidate_id'
        );

        $this->addForeignKey(
            'fk-practical_test_schedule-test_session_id',
            'practical_test_schedule',
            'test_session_id',
            'test_session',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('practical_test_schedule');
    }
}
