<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "candidate_previous_session".
 *
 * @property integer $id
 * @property integer $candidate_id
 * @property integer $test_session_id
 * @property integer $isPass
 * @property string $date_created
 *
 * @property Candidates $candidate
 * @property TestSession $testSession
 */
class CandidatePreviousSession extends \yii\db\ActiveRecord
{
    const TYPE_PASSED = 'Passed';
    const TYPE_FAILED = 'Failed';
    const TYPE_PARTIAL_PASSED = 'Partial Passed';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'candidate_previous_session';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['candidate_id', 'test_session_id'], 'required'],
            [['candidate_id', 'test_session_id', 'isPass'], 'integer'],
            [['date_created','isConfirmed', 'fileLocation', 'craneStatus', 'isGraded', 'remarks'], 'safe']
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
            'isPass' => 'Is Pass',
            'date_created' => 'Date Created',
        ];
    }

    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            $this->date_created=date('Y-m-d', strtotime('now'));
            
            return true;
        }else{
            return false;
        }
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

    public function hasPreviousPdf()
    {
        $candidate = Candidates::findOne($this->candidate_id);
        $realCandidateBaseFolder = realpath(\Yii::$app->basePath) . '/web/app-forms/' . $candidate->getFolderDirectory();
        $candidateFolder = $realCandidateBaseFolder.'/previous-session/' . $this->id;
        $file =  $candidateFolder . '/previous-form.pdf';
        if (is_file($file)) {
            return $file;
        }
        return false;
    }

    public function getPassingType()
    {
        $pass = 0;
        $fail = 0;
        if ($this->craneStatus == null) {
            return self::TYPE_PASSED;
        } else {
            $jsonInfo = json_decode($this->craneStatus, true);
            foreach ($jsonInfo as $craneStat) {
                if ($craneStat['status'] == true) {
                    $pass++;
                } else {
                    $fail++;
                }
            }
        }

        if ($fail == 0 && $pass > 0) {
            return self::TYPE_PASSED;
        } else if($fail != 0 && $pass > 0) {
            return self::TYPE_PARTIAL_PASSED;
        } else if($fail != 0 && $pass == 0) {
            return self::TYPE_FAILED;
        }
    }
}
