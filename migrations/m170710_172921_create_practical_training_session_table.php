<?php

use yii\db\Migration;

/**
 * Handles the creation of table `practical_training_session`.
 */
class m170710_172921_create_practical_training_session_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('practical_training_session', [
            'id' => $this->primaryKey(),
            'test_session_id' => $this->integer()->notNull(),
            'student_id' => $this->integer()->notNull(),
            'start_time' => $this->dateTime()->notNull(),
            'end_time' => $this->dateTime()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull()
        ]);

        $this->createIndex(
            'idx-p-training_session-test_session_id',
            'practical_training_session',
            'test_session_id'
        );

        $this->addForeignKey(
            'fk-p-training_session-test_session_id',
            'practical_training_session',
            'test_session_id',
            'test_session',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-p-training_session-student_id',
            'practical_training_session',
            'student_id'
        );

        $this->addForeignKey(
            'fk-p-training_session-student_id',
            'practical_training_session',
            'student_id',
            'candidates',
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
            'fk-p-training_session-student_id',
            'practical_training_session'
        );

        $this->dropIndex(
            'idx-p-training_session-student_id',
            'practical_training_session'
        );

        $this->dropForeignKey(
            'fk-p-training_session-test_session_id',
            'practical_training_session'
        );

        $this->dropIndex(
            'idx-p-training_session-test_session_id',
            'practical_training_session'
        );

        $this->dropTable('practical_training_session');
    }
}
