<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "practical_test_schedule".
 *
 * @property int $id
 * @property int $candidate_id
 * @property int $test_session_id
 * @property string $type
 * @property string $time
 * @property boolean $practice_time_only
 * @property Candidates $candidate
 * @property TestSession $testSession
 */
class PracticalTestSchedule extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'practical_test_schedule';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['candidate_id', 'test_session_id', 'day'], 'integer'],
            [['practice_hours'], 'number'],
            [['practice_time_only'], 'boolean'],
            [['test_session_id', 'type', 'new_or_retest', 'time', 'day'], 'required'],
            [['type', 'time', 'new_or_retest'], 'string', 'max' => 255],
            ['type', 'in', 'range' => ['TEST', 'MAINTENANCE', 'PRACTICE']],
            ['new_or_retest', 'in', 'range' => ['NEW', 'RETEST', 'NONE']],
            [['candidate_id'], 'exist', 'skipOnError' => true, 'targetClass' => Candidates::className(), 'targetAttribute' => ['candidate_id' => 'id']],
            [['test_session_id'], 'exist', 'skipOnError' => true, 'targetClass' => TestSession::className(), 'targetAttribute' => ['test_session_id' => 'id']],
            [['candidate_id', 'test_session_id', 'day', 'type', 'new_or_retest', 'time'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'candidate_id' => 'Candidate ID',
            'test_session_id' => 'Test Session ID',
            'type' => 'Type',
            'time' => 'Time',
        ];
    }

    public function fields()
    {
        return [
            'id',
            'candidate_id',
            'test_session_id',
            'day',
            'date' => function() {
                $day = $this->day - 1;
                return $this->testDates[$day];
            },
            'type',
            'new_or_retest',
            'time',
            'practice_hours',
            'practice_time_only',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCandidate()
    {
        return $this->hasOne(Candidates::className(), ['id' => 'candidate_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTestSession()
    {
        return $this->hasOne(TestSession::className(), ['id' => 'test_session_id']);
    }

    public function getTestDates()
    {
        $testSession = $this->testSession;
        $startDate = new \DateTimeImmutable($testSession->start_date);
        $endDate = new \DateTimeImmutable($testSession->end_date);

        $numDays = $endDate->diff($startDate)->format('%a');

        $testDays = [$startDate->format('n-j')];
        for ($i = 1; $i < ($numDays + 1); $i++) {
            $testDays[] = $startDate->add(new \DateInterval('P' . $i . 'D'))->format('n-j');
        }

        return $testDays;
    }
}
