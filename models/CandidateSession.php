<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "candidate_session".
 *
 * @property integer $id
 * @property integer $candidate_id
 * @property integer $test_session_id
 * @property integer $application_type_id
 * @property string $promoCode
 * @property string $transactionId
 * @property double $amount
 * @property string $date_created
 * @property string $date_updated
 *
 * @property ApplicationType $applicationType
 * @property Candidates $candidate
 * @property TestSession $testSession
 */
class CandidateSession extends \yii\db\ActiveRecord
{
    public $application_type_id;
    public $promoCode;
    public $candidateName;
    public $isPurchaseOrder;
    public $first_name;
    public $last_name;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'candidate_session';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['candidate_id', 'test_session_id'], 'required'],
            [['candidate_id', 'test_session_id','application_type_id'], 'integer'],
            [['date_created', 'date_updated', 'promoCode','isPurchaseOrder', 'candidateName', 'isPass'], 'safe']
        ];
    }

    function getFirstName()
    {
         return $this->getCandidate()->all()[0]->first_name;
    }

    function getLastName()
    {
        return $this->getCandidate()->all()[0]->last_name;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'candidate_id' => 'Candidate ID',
            'candidateName' => 'Candidate Name',
            'test_session_id' => 'Test Session ID',
            'date_created' => 'Date Created',
            'date_updated' => 'Date Updated',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCandidate()
    {
        return $this->hasOne(Candidates::className(), ['id' => 'candidate_id']);
    }

    public function getCandidateApplicationTypeDesc()
    {
        return $this->getCandidate()->all()[0]->getApplicationTypeDesc();
    }

    public function getCandidatePromoCode()
    {
        return $this->getCandidate()->all()[0]->referralCode;
    }

    public function getCandidateIsPurchaseOrder()
    {
        return $this->getCandidate()->all()[0]->isPurchaseOrder == 1 ? 'Yes' : 'No';
    }

    public function getCandidateName()
    {
        $candidate = Candidates::findOne($this->candidate_id);
        return $candidate->getFullName();
    }

    public function getCandidatePhone()
    {
        $candidate = Candidates::findOne($this->candidate_id);
        return $candidate->phone;
    }

    public function getTestSession()
    {
        return $this->hasOne(TestSession::className(), ['id' => 'test_session_id']);
    }

    public function getFullTestSessionDescription(){
        $testSession = TestSession::findOne($this->test_session_id);
        if($testSession != null)
            return $testSession->getFullTestSessionDescription();
        return '';
    }

    public function getTestSessionTypeId(){
        $testSession = TestSession::findOne($this->test_session_id);
        if($testSession != null)
            return $testSession->getTestSessionTypeId();
        return false;
    }
}
