<?php

namespace app\models;

use Yii;
use app\helpers\UtilityHelper;
use app\helpers\AppFormHelper;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "candidates".
 *
 * @property integer $id
 * @property string $first_name
 * @property string $last_name
 * @property string $middle_name
 * @property string $email
 * @property string $phone
 * @property string $address
 * @property string $city
 * @property string $state
 * @property string $zip
 * @property string $company_name
 * @property string $company_fax
 * @property string $company_phone
 * @property string $company_address
 * @property string $company_city
 * @property string $company_state
 * @property string $company_zip
 * @property string $contact_person
 * @property string $date_created
 * @property string $date_updated
 * @property string $cco_id
 */
class Candidates extends \yii\db\ActiveRecord
{
    public $confirmEmail;
    public $ssn;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'candidates';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'email', 'phone', 'birthday', 'application_type_id', 'address', 'city', 'zip', 'cco_id'], 'required'],
            [['isRetake', 'retakeType', 'isArchived', 'written_nccco_fee_override', 'practical_nccco_fee_override', 'date_created', 'date_updated', 'suffix','isPurchaseOrder' ,'ccoCertNumber', 'ssn1', 'ssn2', 'ssn3', 'birthday', 'cellNumber', 'faxNumber', 'requestAda', 'referralCode', 'referralPaid', 'registration_step', 'disregard', 'branding', 'survey', 'surveyOther', 'ad_online_info', 'friend_email', 'photo', 'instructor_notes', 'collect_payment_override'], 'safe'],
            [['email', 'contactEmail'], 'email'],
            [['ssn'], 'checkSsn'],
            [['custom_form_setup', 'signedForms', 'instructor_notes'], 'string'],
            [['written_nccco_fee_override', 'practical_nccco_fee_override', 'practice_time_credits'], 'number'],
            [['confirmEmail'], 'checkEmail'],
            [['first_name', 'last_name', 'middle_name', 'email', 'phone', 'address', 'city', 'state', 'zip', 'company_name', 'company_fax', 'company_phone', 'company_address', 'company_city', 'company_state', 'company_zip', 'contact_person', 'invoice_number', 'purchase_order_number'], 'string', 'max' => 250],
            [['address', 'company_address'], 'validateSameAddress'],
            [['is_company_sponsored', 'collect_payment_override'], 'boolean'],
            [['cco_id'], 'string', 'min' => 9, 'max' => 9],
        ];
    }

    public function validateSameAddress($attribute, $params)
    {
        $homeAddress = strtolower(preg_replace('/\s+/', '', $this->address));
        $companyAddress = strtolower(preg_replace('/\s+/', '', $this->company_address));

        $bothBlank = $homeAddress === '' && $companyAddress === '';

        if ($homeAddress === $companyAddress && !$bothBlank) {
            $message = 'Home Address should not be the same as the Company Address.';
            $this->addError('address', $message);
            $this->addError('company_address', $message);
        }
    }

    public function checkEmail($attribute, $params)
    {
        if ($this->$attribute != $this->email) {
            $this->addError($attribute, 'Email does not match');
        }
    }

    public function checkSsn($attribute, $params)
    {
        if ($this->$attribute != '' && strlen($this->$attribute) != 4) {
            $this->addError($attribute, 'SSN # is invalid, it should be in 9999 format' );
            return false;
        }
        return true;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->date_updated = date_format(date_create('now'), 'Y-m-d H:i:s');

            if ($this->isNewRecord && !isset($this->date_created)) {
                $this->date_created = date_format(date_create('now'), 'Y-m-d H:i:s');
            }

            if ($this->isNewRecord) {
                $customForm = [];
                $this->custom_form_setup = json_encode($customForm);
            }
            return true;
        } else {
            return false;
        }
    }

    public function getSsn(){
        return $this->ssn3;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'middle_name' => 'Middle Name',
            'confirmEmail' => 'Confirm Email',
            'email' => 'Email',
            'phone' => 'Phone',
            'address' => 'Home Address',
            'city' => 'City',
            'state' => 'State',
            'zip' => 'Zip',
            'company_name' => 'Company Name',
            'company_fax' => 'Company Fax',
            'company_phone' => 'Company Phone',
            'company_address' => 'Company Address',
            'company_city' => 'Company City',
            'company_state' => 'Company State',
            'company_zip' => 'Company Zip',
            'contact_person' => 'Contact Person',
            'date_created' => 'Date Created',
            'date_updated' => 'Date Updated',
            'cco_id' => 'CCO ID',
            'ccoCertNumber' => 'CCO CERTIFICATION NUMBER (if previously certified)',
            'ssn' => 'SSN# (LAST THREE)',
            'birthday' => 'Date of Birth',
            'requestAda' => "I AM REQUESTING TESTING ACCOMMODATIONS IN COMPLIANCE WITH THE AMERICAN WITH DISABILITIES ACT (ADA).
(For details on NCCCO's Testing Accommodations policy, please see www.nccco.org/accommodations.)",
            'survey' => 'Where did you here about us?',
        ];
    }

    public function getApplicationType()
    {
        return $this->hasOne(ApplicationType::className(), ['id' => 'application_type_id']);
    }

    public function getTransactions()
    {
        return $this->hasMany(CandidateTransactions::className(), ['candidateId' => 'id']);
    }

    public function getPendingTransactions()
    {
        return $this->hasMany(PendingTransaction::className(), ['candidate_id' => 'id']);
    }

    public function getSurveyOptions() {
        return [
            'a friend' => 'a friend',
            'facebook' => 'facebook',
            'instagram' => 'instagram',
            'google' => 'google',
            'website' => 'website'
        ];
    }

    public function getTransactionTotals()
    {
        $transactions = $this->transactions;

        $totalPayment = 0;
        $totalCharged = 0;
        $totalRefunded = 0;
        $totalPromo = 0;
        $totalRemovedCharge = 0;

        foreach($transactions as $transaction) {
            if ($transaction->paymentType == CandidateTransactions::TYPE_STUDENT_CHARGE) {
                $totalCharged += $transaction->amount;
            } else if ($transaction->paymentType == CandidateTransactions::TYPE_CASH
                || $transaction->paymentType == CandidateTransactions::TYPE_INTUIT
                || $transaction->paymentType == CandidateTransactions::TYPE_RECEIVABLES_OTHER
                || $transaction->paymentType == CandidateTransactions::TYPE_CHEQUE
                || $transaction->paymentType == CandidateTransactions::TYPE_ELECTRONIC_PAYMENT
                || $transaction->paymentType == CandidateTransactions::TYPE_SQUARE
            ) {
                $totalPayment += $transaction->amount;
            } else if ($transaction->paymentType == CandidateTransactions::TYPE_TRANSFER) {
                $totalPayment -= $transaction->amount;
            } else if ($transaction->paymentType == CandidateTransactions::TYPE_REFUND) {
                $totalRefunded += $transaction->amount;
            } else if ($transaction->paymentType == CandidateTransactions::TYPE_DISCOUNT) {
                $totalRemovedCharge += $transaction->amount;
            } else if ($transaction->paymentType == CandidateTransactions::TYPE_PROMO) {
                $totalPromo += $transaction->amount;
            }
        }

        $totalNetPayable = $totalCharged + $totalRefunded - $totalPromo - $totalRemovedCharge;
        $totalAmountOwed = $totalNetPayable - $totalPayment;

        return [
            'totalPayment' => $totalPayment,
            'totalCharged' => $totalCharged,
            'totalRefunded' => $totalRefunded,
            'totalPromo' => $totalPromo,
            'totalRemovedCharge' => $totalRemovedCharge,
            'totalNetPayable' => $totalNetPayable,
            'totalAmountOwed' => $totalAmountOwed
        ];
    }

    public function getAmountOwed()
    {
        $transactions = $this->transactions;

        $result = array_reduce($transactions, function($total, $transaction) {
            if (in_array($transaction->paymentType, CandidateTransactions::ADDITIONS)) {
                return $total - $transaction->amount;
            }

            if (in_array($transaction->paymentType, CandidateTransactions::DEDUCTIONS)) {
                return $total + $transaction->amount;
            }
            return $total;
        }, 0);

        return $result;
    }

    public function getFullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getFolderDirectory()
    {
        return md5($this->id);
    }

    public function getPreviousSessions()
    {
        return $this->hasMany(CandidatePreviousSession::className(), ['candidate_id' => 'id']);
    }

    public function getPracticalTestSchedule()
    {
        return $this->hasMany(PracticalTestSchedule::className(), ['candidate_id' => 'id']);
    }

    public function getPracticalTrainingSessions()
    {
        return $this->hasMany(PracticalTrainingSession::className(), ['student_id' => 'id']);
    }

    public function getTrainingSessions()
    {
        return $this->hasMany(CandidateTrainingSession::className(), ['candidate_id' => 'id']);
    }

    public function getScoreSheetPhotos()
    {
        return $this->hasMany(CandidateSessionExamPhoto::className(), ['candidateId' => 'id']);
    }

    public function getDeclinedTests()
    {
        return $this->hasMany(CandidateDeclineTestAttestation::className(), ['candidate_id' => 'id']);
    }

    public function getApplicationTypeDesc()
    {
        return $this->getApplicationType()->all()[0]->name;
    }

    public function getApplicationTypeKeyword()
    {
        return $this->getApplicationType()->all()[0]->keyword;
    }

    public function getApplicationFormCranes()
    {
        $practicalForm = $this->applicationType->getApplicationFormSetups()->where(['form_name' => 'iai-blank-practical-test-application-form'])->one();

        $result = [];

        if (isset($practicalForm)) {
            $formSetup = json_decode($practicalForm->form_setup, true);

            if (isset($formSetup['P_TELESCOPIC_TLL']) && $formSetup['P_TELESCOPIC_TLL'] === 'on') {
                $result[] = 'sw';
            }

            if (isset($formSetup['P_TELESCOPIC_TSS']) && $formSetup['P_TELESCOPIC_TSS'] === 'on') {
                $result[] = 'fx';
            }
        }

        return $result;
    }

    public function hasNoSession()
    {
        $testSessions = $this->getAllTestSession();
        if ($testSessions == null || count($testSessions) == 0) {
            return true;
        }
        return false;
    }

    public function getAllTestSession()
    {
        return CandidateSession::findAll(['candidate_id' => $this->id]);
    }

    public function getWrittenTestSession()
    {
        $testSessions = $this->getAllTestSession();
        foreach ($testSessions as $testSession) {
            if ($testSession->getTestSessionTypeId() == TestSite::TYPE_WRITTEN) {
                return $testSession;
            }
        }
        return false;
    }

    public function getPracticalSession()
    {
        $testSessions = $this->getAllTestSession();
        foreach ($testSessions as $testSession) {
            if ($testSession->getTestSessionTypeId() == TestSite::TYPE_PRACTICAL) {
                return $testSession;
            }
        }
        return false;
    }

    public function getPaymentLists()
    {
        $candidateTransactions = CandidateTransactions::find()->where('candidateId = '.$this->id)->orderBy('id asc')->all();
        return $candidateTransactions;
    }

    public function hasAppForms()
    {
        return  UtilityHelper::getOriginalAppFormsByCandidateId($this->id);
    }

    public function hasSignedForms()
    {
        if ($this->signedForms == null || $this->signedForms == '') {
            return false;
        }
        if ($this->signedForms != '') {
            $forms = json_decode($this->signedForms, true);
            $forms = $forms == null ? [] : $forms;
            foreach($forms as $name => $val){
                if ($val != '') {
                    return true;
                }
            }
        }
        return false;
    }

    public function getSignedForm($formName)
    {
        if ($this->hasSignedForms()) {
            $forms = json_decode($this->signedForms, true);
            $forms = $forms == null ? [] : $forms;
            foreach($forms as $name => $val){
                if($name == $formName && $val != ''){
                    return $val;
                }
            }
        }
        return false;
    }

    public function isManualConfirmed($formName)
    {
        if ($this->hasSignedForms()) {
            $forms = json_decode($this->signedForms, true);
            $forms = $forms == null ? [] : $forms;
            $manualFormKey = $formName.'-manual-confirm';
            foreach ($forms as $name => $val) {
                if ($name == $manualFormKey && $val != '') {
                    return true;
                }
            }
        }
        return false;
    }

    public function getUnsignedForms()
    {
        $appForms = ApplicationTypeFormSetup::findAll(['application_type_id' => $this->application_type_id]);
        $unsigned = array();

        foreach ($appForms as $form) {
            if ($this->getSignedForm($form->form_name) === false) {
                $unsigned[] = $form->form_name;
            }
        }
        return $unsigned;
    }

    public static function getIncompleteApplication($resultsPerPage , $page)
    {
        $resp = array();
        $resp['list'] = Candidates::find()->where('disregard = 0 and registration_step in (1,2) order by date_created desc limit '.$resultsPerPage.' offset '.(($page-1)*$resultsPerPage))->all();
        $resp['count'] = Candidates::find()->where('disregard = 0 and registration_step in (1,2) ')->count();
        return $resp;
    }

    public static function getRecentApplication($resultsPerPage , $page, $numberOfDays = 1)
    {
        $resp = array();
        $resp['list'] = Candidates::find()->where('registration_step not in (1,2) and  date_created  > DATE_SUB(CURDATE(), INTERVAL '.$numberOfDays.' DAY) order by date_created desc limit '.$resultsPerPage.' offset '.(($page-1)*$resultsPerPage))->all();
        $resp['count'] = Candidates::find()->where('registration_step not in (1,2) and date_created  > DATE_SUB(CURDATE(), INTERVAL '.$numberOfDays.' DAY)')->count();
        return $resp;
    }

    public function removeCharge($amount, $remarks = '')
    {
        $candidateTransaction = new CandidateTransactions();
        $candidateTransaction->amount = $amount;
        $candidateTransaction->candidateId = $this->id;
        $candidateTransaction->paymentType = CandidateTransactions::TYPE_DISCOUNT;
        $candidateTransaction->remarks = $remarks;
        $candidateTransaction->save();
    }

    public function makeAdjustment($amount, $remarks = '')
    {
        $candidateTransaction = new CandidateTransactions();
        $candidateTransaction->amount = $amount;
        $candidateTransaction->candidateId = $this->id;
        $candidateTransaction->paymentType = CandidateTransactions::TYPE_ADJUSTMENT;
        $candidateTransaction->remarks = $remarks;
        $candidateTransaction->save();
    }

    public function addTransfer($amount, $remarks = '')
    {
        $candidateTransaction = new CandidateTransactions();
        $candidateTransaction->amount = $amount;
        $candidateTransaction->candidateId = $this->id;
        $candidateTransaction->paymentType = CandidateTransactions::TYPE_TRANSFER;
        $candidateTransaction->remarks = $remarks;
        $candidateTransaction->save();
    }

    public function getCandidateFormSetup()
    {
        $customForms = json_decode($this->custom_form_setup, true);
        $customForms = $customForms == null ? [] : $customForms;
        $appTypeForms = ApplicationTypeFormSetup::findAll(['application_type_id' => $this->application_type_id]);
        $defaultFormSetupInfo = [];

        foreach ($appTypeForms as $form) {
            $defaultFormSetupInfo[$form->form_name] = json_decode($form->form_setup, true);
            if ($this->custom_form_setup != null && isset($customForms[$form->form_name])) {
                $customFormData = $customForms[$form->form_name];
                $defaultFormSetupInfo[$form->form_name] = ($customFormData);
            }
        }

        return $defaultFormSetupInfo;
    }

    public function getCranes($testSessionId)
    {
        $testSession = TestSession::findOne($testSessionId);
        $craneList = [];

        $formName = '';
        $inputName = '';
        $customForms = json_decode($this->custom_form_setup, true);
        $customForms = $customForms == null ? [] : $customForms;

        $appTypeForms = ApplicationTypeFormSetup::findAll(['application_type_id' => $this->application_type_id]);
        $defaultFormSetupInfo = [];

        $craneKey = false;
        if ($testSession->getTestSessionTypeId() == TestSite::TYPE_PRACTICAL) {
            $formName = AppFormHelper::PRACTICAL_FORM_PDF;
            $craneKey = 'practical-cranes';
        } else if($testSession->getTestSessionTypeId() == TestSite::TYPE_WRITTEN) {
            if (AppFormHelper::hasRecertifyPdf($this->application_type_id)) {
                $formName = AppFormHelper::RECERTIFY_FORM_PDF;
            } else {
                $formName = AppFormHelper::WRITTEN_FORM_PDF;
            }
            $craneKey = 'cranes';
        }

        foreach ($appTypeForms as $form) {
            if ($form->form_name == $formName) {
                $defaultFormSetupInfo = json_decode($form->form_setup, true);
            }
        }

        if ($this->custom_form_setup != null && isset($customForms[$formName])) {
            $customFormData = $customForms[$formName];
            $defaultFormSetupInfo = ($customFormData);
        }

        $dynaForms = $defaultFormSetupInfo;
        $dynamicPracticalFormInput = AppFormHelper::getDynamicFormInfo()[$formName][$craneKey];
        foreach ($dynaForms as $key => $val) {
            foreach ($dynamicPracticalFormInput as $formKey => $formval) {
                if ($key == $formKey &&  $val == 'on') {
                    $craneInfo = [];
                    $craneInfo['key'] = $formKey;
                    $craneInfo['name'] = $formval['name'];
                    $craneList[] = $craneInfo;
                }
            }
        }

        return $craneList;
    }

    public function getPreviousGrades($currentTestSessions = [])
    {
        $previousSessions = CandidatePreviousSession::find()->where(['candidate_id' => $this->id])->andWhere(['not in', 'test_session_id', $currentTestSessions])->orderBy(['date_created' => SORT_ASC])->all();
        $previousGrades = array_reduce($previousSessions, function ($sessionAcc, $session) {
            $newSessionAcc = $sessionAcc;

            $gradeArr = json_decode($session->craneStatus, true);
            $grade = array_reduce($gradeArr, function ($acc, $grade) {
                $newAcc = $acc;
                $newAcc[$grade['key']] = (int) $grade['val'];
                return $newAcc;
            }, []);

            return array_merge($newSessionAcc, $grade);
        }, []);

        return $previousGrades;
    }

    public function getTotalIAIFee()
    {
        if (isset($this->written_nccco_fee_override)) {
            return $this->written_nccco_fee_override;
        }

        $customForms = json_decode($this->custom_form_setup, true);
        $customForms = $customForms == null ? [] : $customForms;
        $appTypeForms = ApplicationTypeFormSetup::findAll(['application_type_id' => $this->application_type_id]);

        $dynamicForms = AppFormHelper::getDynamicFormInfo();
        $totalIaiFee = 0;
        foreach ($dynamicForms as $formName => $formSpecific) {
            $defaultFormSetupInfo = [];
            foreach ($appTypeForms as $form) {
                if ($form->form_name == $formName) {
                    $defaultFormSetupInfo = json_decode($form->form_setup, true);
                }
            }

            if ($this->custom_form_setup != null && isset($customForms[$formName])) {
                $customFormData = $customForms[$formName];
                $defaultFormSetupInfo = ($customFormData);
            }

            $dynaForms = $defaultFormSetupInfo;

            $appFormDynamic = new ApplicationTypeFormSetup();
            $appFormDynamic->form_setup = json_encode($dynaForms);

            if (isset($formSpecific['iai'])) {
                foreach ($formSpecific['iai'] as $keyName => $obj) {
                    if (UtilityHelper::isDynamicFieldChecked($appFormDynamic, $keyName)) {
                        $totalIaiFee += $obj['amount'];
                    }
                }
            }
        }
        return $totalIaiFee;
    }

    public function getSchool()
    {
        $writtenTestSession = false;
        $practicalTestSession = false;
        $candidateSessions = $this->getAllTestSession();
        foreach ($candidateSessions as $testSession) {
            if ($testSession->getTestSessionTypeId() == TestSite::TYPE_WRITTEN) {
                $writtenTestSession = TestSession::findOne($testSession->test_session_id);
            } else if($testSession->getTestSessionTypeId() == TestSite::TYPE_PRACTICAL) {
                $practicalTestSession = TestSession::findOne($testSession->test_session_id);
            }
        }
        $schoolType = 'ccs';
        if ($writtenTestSession !== false) {
            $schoolType = strtolower($writtenTestSession->school);
        } else if ($practicalTestSession !== false) {
            $schoolType = strtolower($practicalTestSession->school);
        }
        return $schoolType;
    }

    public function hasPreviousWrittenSession()
    {
        return count($this->getAllPreviousWrittenSession()) == 0 ? false : true;
    }

    public function hasPreviousPracticalSession()
    {
        return count($this->getAllPreviousPracticalSession()) == 0 ? false : true;
    }

    public function getAllPreviousWrittenSession()
    {
        $testSessions = $this->getPreviousSessions()->orderBy('id ASC')->all();
        $writtenSessions = [];
        if($testSessions && count($testSessions) > 0){
            foreach($testSessions as $ses){
                $test = TestSession::findOne($ses->test_session_id);
                if($test->getTestSessionTypeId() == TestSite::TYPE_WRITTEN){
                    $writtenSessions[] = $ses;
                }
            }
        }
        return $writtenSessions;
    }

    public function getAllPreviousPracticalSession()
    {
        $testSessions = $this->getPreviousSessions()->orderBy('id ASC')->all();
        $practicalSessions = [];
        if ($testSessions && count($testSessions) > 0) {
            foreach ($testSessions as $ses) {
                $test = TestSession::findOne($ses->test_session_id);
                if ($test->getTestSessionTypeId() == TestSite::TYPE_PRACTICAL) {
                    $practicalSessions[] = $ses;
                }
            }
        }
        return $practicalSessions;
    }

    public function getOriginalWrittenSession()
    {
        $prevWrittens = $this->getAllPreviousWrittenSession();
        if (count($prevWrittens) > 0) {
            $ses = TestSession::findOne($prevWrittens[0]->test_session_id);
            return $ses;
        }
        return $this->getWrittenTestSession();
    }

    public function getOriginalPracticalSession()
    {
        $prevPractical = $this->getAllPreviousPracticalSession();
        if (count($prevPractical) > 0) {
            $ses = TestSession::findOne($prevPractical[0]->test_session_id);
            return $ses;
        }
        return $this->getPracticalSession();
    }

    public function hasPreviousSessions()
    {
        $testSessions = CandidatePreviousSession::findAll(['candidate_id' => $this->id, 'isGraded' => 1]);
        if ($testSessions && count($testSessions) > 0) {
            return true;
        }
        return false;
    }

    public function hasPreviousSessionsIncludingNonGraded()
    {
        $testSessions = CandidatePreviousSession::findAll(['candidate_id' => $this->id]);
        if ($testSessions && count($testSessions) > 0) {
            return true;
        }
        return false;
    }

    public static function cancelSession($candidateId, $testSessionId)
    {
        CandidateSession::findOne([
            'candidate_id' => $candidateId,
            'test_session_id' => $testSessionId
        ])->delete();
    }

    public function getMergedFormSetup()
    {
        $applicationType = $this->applicationType;

        $defaultFormSetup = array_reduce($applicationType->applicationFormSetups, function($acc, $form) {
            return array_merge($acc, json_decode($form->form_setup, true));
        }, []);

        $customFormSetup = array_reduce(json_decode($this->custom_form_setup, true), function($acc, $formSetup) {
            return array_merge($acc, $formSetup);
        }, []);

        $mergedFormSetup = array_merge($defaultFormSetup, $customFormSetup);

        return $mergedFormSetup;
    }

    public function getGrades()
    {
        $previousSessions = CandidatePreviousSession::find()->where(['candidate_id' => $this->id])->orderBy(['date_created' => SORT_ASC])->all();

        if (isset($previousSessions)) {
            $grades = ArrayHelper::toArray($previousSessions, [
                'app\models\CandidatePreviousSession' => [
                    'date_created',
                    'grades' => function($session) {
                        $grade = json_decode($session->craneStatus, true);

                        return array_reduce($grade, function($acc, $test) {
                            if (isset($test['val'])) {
                                $newAcc = $acc;
                                $newAcc[$test['key']] = $test['val'];
                                return $newAcc;
                            }
                            return $acc;
                        }, []);
                    }
                ]
            ]);

            $gradesSummary = array_reduce($grades, function($acc, $test) {
                return array_merge($acc, $test['grades']);
            }, []);

            return $gradesSummary;
        }

        return null;
    }

    public function updateGrades($newGrades, $resetGrades = false)
    {
        $candidateSessions = CandidateSession::findAll(['candidate_id' => $this->id]);
        $mergedFormSetup = $this->mergedFormSetup;

        $writtenSessionId = array_reduce($candidateSessions, function($acc, $session) {
            $testSessionType = $session->testSession->testSessionType;
            if ($testSessionType == 'Written') {
                return $session->test_session_id;
            }
            return $acc;
        });

        $practicalSessionId = array_reduce($candidateSessions, function($acc, $session) {
            $testSessionType = $session->testSession->testSessionType;
            if ($testSessionType == 'Practical') {
                return $session->test_session_id;
            }
            return $acc;
        });

        $testNames = [
            'W_EXAM_CORE' => 'Mobile Core Exam',
            'W_EXAM_LBC' => 'Lattice Boom Crawler (LBC)',
            'W_EXAM_LBT' => 'Lattice Boom Truck (LBT)',
            'W_EXAM_BTF' => 'Boom Truck-Fixed Cab (BTF)',
            'W_EXAM_TOWER' => 'Tower Crane',
            'W_EXAM_OVERHEAD' => 'Overhead Crane',
            'W_EXAM_TLL' => 'Telescopic Boom-Swing Cab (TLL)',
            'W_EXAM_TSS' => 'Telescopic Boom-Fixed Cab (TSS)',
            'W_EXAM_ADD_LBC' => 'Lattice Boom Crawler (LBC) (Recert Additional)',
            'W_EXAM_ADD_LBT' => 'Lattice Boom Truck (LBT) (Recert Additional)',
            'W_EXAM_ADD_TLL' => 'Telescopic Boom-Swing Cab (TLL)',
            'W_EXAM_ADD_TSS' => 'Telescopic Boom-Fixed Cab (TSS)',
            'W_EXAM_ADD_BTF' => 'Boom Truck-Fixed Cab (BTF)',
            'W_EXAM_ADD_TOWER' => 'Tower Crane',
            'W_EXAM_ADD_OVERHEAD' => 'Overhead Crane',
            'P_LATTICE' => 'Lattice Boom Crane',
            'P_TOWER' => 'Tower Crane',
            'P_OVERHEAD' => 'Overhead Crane',
            'P_TELESCOPIC_TLL' => 'Telescopic Boom Crane - Swing Cab (TLL)',
            'P_TELESCOPIC_TSS' => 'Telescopic Boom Crane - Fixed Cab (TSS)'
        ];

        $prepareGradePayload = function($newGrades, $mergedFormSetup, $prefix) {
            $grades = [];
            foreach ($newGrades as $key => $grade) {
                $isTakingTest = isset($mergedFormSetup[$key]) && $mergedFormSetup[$key] === 'on';
                if ($isTakingTest && substr($key, 0, 2) == $prefix) {
                    $grades[$key] = $grade;
                }
            }

            return $grades;
        };

        $writtenGrades = $prepareGradePayload($newGrades, $mergedFormSetup, 'W_');
        $practicalGrades = $prepareGradePayload($newGrades, $mergedFormSetup, 'P_');

        $prepareCraneStatusPayload = function($prevSession, $gradesPayload) use ($testNames, $resetGrades) {
            $craneStatus = $resetGrades ? [] : json_decode($prevSession->craneStatus, true);

            foreach ($gradesPayload as $test => $grade) {
                $gradeEntry = [];
                $gradeEntry['key'] = $test;
                $gradeEntry['name'] = isset($testNames[$test]) ? $testNames[$test] : 'Unrecognized Test';
                $gradeEntry['status'] = true;
                $gradeEntry['val'] = $grade;

                if ($resetGrades) {
                    $craneStatus[] = $gradeEntry;
                    continue;
                }

                $testIndex = null;
                foreach ($craneStatus as $index => $prevGradeEntry) {
                    if ($prevGradeEntry['key'] === $gradeEntry['key']) {
                        $testIndex = $index;
                    }
                }

                if (isset($testIndex)) {
                    $craneStatus[$testIndex] = $gradeEntry;
                } else {
                    $craneStatus[] = $gradeEntry;
                }
            }

            return $craneStatus;
        };

        $updatePreviousSession = function($prevSessionId, $grades) use ($prepareCraneStatusPayload) {
            $prevSession = CandidatePreviousSession::findOne([
                'candidate_id' => $this->id,
                'test_session_id' => $prevSessionId
            ]);

            if (!isset($prevSession)) {
                $prevSession = new CandidatePreviousSession();
                $prevSession->craneStatus = '[]';
            }

            $prevSession->candidate_id = $this->id;
            $prevSession->test_session_id = $prevSessionId;
            $prevSession->isGraded = '1';

            $craneStatus = $prepareCraneStatusPayload($prevSession, $grades);

            $prevSession->craneStatus = json_encode($craneStatus);
            $prevSession->save();
        };

        if ($writtenSessionId) {
            $updatePreviousSession($writtenSessionId, $writtenGrades);
        }

        if ($practicalSessionId) {
            $updatePreviousSession($practicalSessionId, $practicalGrades);
        }
    }

    public function getHasWrittenTest()
    {
        $mergedFormSetup = $this->mergedFormSetup;

        $hasWritten = array_reduce(array_keys($mergedFormSetup), function($acc, $fieldKey) use($mergedFormSetup) {
            if (substr($fieldKey, 0, 2) === 'W_') {
                return $acc || $mergedFormSetup[$fieldKey] === 'on';
            }
            return $acc;
        }, false);

        return $hasWritten;
    }

    public function getPracticalExams()
    {
        $mergedFormSetup = $this->mergedFormSetup;

        $practicalExams = array_reduce(array_keys($mergedFormSetup), function($acc, $fieldKey) use($mergedFormSetup) {
            if (substr($fieldKey, 0, 2) === 'P_' && $mergedFormSetup[$fieldKey] === 'on') {
                $newAcc = $acc;
                $newAcc[] = $fieldKey;
                return $newAcc;
            }
            return $acc;
        }, []);

        return $practicalExams;
    }
}
