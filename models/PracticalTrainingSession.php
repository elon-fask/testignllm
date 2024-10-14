<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "practical_training_session".
 *
 * @property integer $id
 * @property integer $test_session_id
 * @property integer $student_id
 * @property string $start_time
 * @property string $end_time
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Candidates $student
 * @property TestSession $testSession
 */
class PracticalTrainingSession extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'practical_training_session';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['test_session_id', 'student_id', 'start_time', 'end_time'], 'required'],
            [['test_session_id', 'student_id'], 'integer'],
            [['test_session_id', 'student_id', 'start_time', 'end_time'], 'safe'],
            [['student_id'], 'exist', 'skipOnError' => true, 'targetClass' => Candidates::className(), 'targetAttribute' => ['student_id' => 'id']],
            [['test_session_id'], 'exist', 'skipOnError' => true, 'targetClass' => TestSession::className(), 'targetAttribute' => ['test_session_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'test_session_id' => 'Test Session ID',
            'student_id' => 'Student ID',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if ($insert) {
            $this->created_at = date('Y-m-d H:i:s');
        }
        $this->updated_at = date('Y-m-d H:i:s');
        return true;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudent()
    {
        return $this->hasOne(Candidates::className(), ['id' => 'student_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTestSession()
    {
        return $this->hasOne(TestSession::className(), ['id' => 'test_session_id']);
    }
}
