<?php

use yii\db\Migration;

/**
 * Handles the creation of table `candidate_training_photo`.
 */
class m180423_124330_create_candidate_training_photo_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('candidate_training_photo', [
            'id' => $this->primaryKey(),
            'training_session_id' => $this->integer()->notNull(),
            's3_key' => $this->string()->notNull(),
            'uploaded_by' => $this->integer(),
            'created_at' => $this->dateTime()
        ]);

        $this->createIndex(
            'idx-candidate_training_photo-training_session_id',
            'candidate_training_photo',
            'training_session_id'
        );

        $this->addForeignKey(
            'fk-c_training_photo_training_session_id-c_training_session_id',
            'candidate_training_photo',
            'training_session_id',
            'candidate_training_session',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-candidate_training_photo-uploaded_by',
            'candidate_training_photo',
            'uploaded_by'
        );

        $this->addForeignKey(
            'fk-candidate_training_photo_uploaded_by-user_user_id',
            'candidate_training_photo',
            'uploaded_by',
            'user',
            'id',
            'SET NULL'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey(
            'fk-candidate_training_photo_uploaded_by-user_user_id',
            'candidate_training_photo'
        );

        $this->dropIndex(
            'idx-candidate_training_photo-uploaded_by',
            'candidate_training_photo'
        );

        $this->dropForeignKey(
            'fk-c_photo_training_session_id-c_training_session_id',
            'candidate_training_photo'
        );

        $this->dropIndex(
            'idx-candidate_training_photo-training_session_id',
            'candidate_training_photo'
        );

        $this->dropTable('candidate_training_photo');
    }
}
