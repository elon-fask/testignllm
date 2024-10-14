<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "candidate_session_bookkeeping".
 *
 * @property integer $id
 * @property integer $candidateId
 * @property integer $testSessionId
 * @property integer $isNCCCOPaid
 * @property integer $hasExcuseLetter
 * @property string $date_created
 */
class CandidateSessionBookkeeping extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'candidate_session_bookkeeping';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['candidateId', 'testSessionId'], 'required'],
            [['candidateId', 'testSessionId', 'isNCCCOPaid', 'hasExcuseLetter'], 'integer'],
            [['date_created'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'candidateId' => 'Candidate ID',
            'testSessionId' => 'Test Session ID',
            'isNCCCOPaid' => 'Is Ncccopaid',
            'hasExcuseLetter' => 'Has Excuse Letter',
            'date_created' => 'Date Created',
        ];
    }
}
