<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "candidate_session_transactions".
 *
 * @property integer $id
 * @property integer $candidateSessionId
 * @property double $amount
 * @property string $transactionId
 * @property string $date_created
 */
class CandidateTransactions extends \yii\db\ActiveRecord
{
    const TYPE_CASH = 1;
    const TYPE_CHEQUE = 2;
    const TYPE_PROMO = 3;
    const TYPE_ELECTRONIC_PAYMENT = 4;
    const TYPE_INTUIT = 5;
    const TYPE_RECEIVABLES_OTHER = 6;
    const TYPE_SQUARE = 7;
    const TYPE_STUDENT_CHARGE = 10;
    const TYPE_REFUND = 20;
    const TYPE_DISCOUNT = 30;
    const TYPE_ADJUSTMENT = 31;
    const TYPE_TRANSFER = 40;

    const SUBTYPE_PRACTICAL_RETEST = 50;
    const SUBTYPE_NCCCO_OTHERS = 60;
    const SUBTYPE_OTHERS = 70;
    const SUBTYPE_WALK_IN_FEE = 71;
    const SUBTYPE_CHANGE_FEE = 72;
    const SUBTYPE_ADD_PRACTICE_TIME = 73;
    const SUBTYPE_LATE_FEE = 74;

    const NCCCO_FEE_SUBTYPES = [
        self::SUBTYPE_PRACTICAL_RETEST => 'Practical Retest Fee',
        self::SUBTYPE_NCCCO_OTHERS => 'NCCCO Other Fee',
        self::SUBTYPE_CHANGE_FEE => 'Change Fee/Incomplete Application Fee',
        self::SUBTYPE_WALK_IN_FEE => 'Walk-in Fee',
        self::SUBTYPE_LATE_FEE => 'Late Fee'
    ];

    const DEFAULT_CHARGES = [
        self::SUBTYPE_CHANGE_FEE => 30,
        self::SUBTYPE_WALK_IN_FEE => 100,
        self::SUBTYPE_LATE_FEE => 50
    ];

    const ADDITIONS = [
        self::TYPE_CASH,
        self::TYPE_CHEQUE,
        self::TYPE_PROMO,
        self::TYPE_ELECTRONIC_PAYMENT,
        self::TYPE_INTUIT,
        self::TYPE_RECEIVABLES_OTHER,
        self::TYPE_SQUARE,
        self::TYPE_DISCOUNT,
        self::TYPE_ADJUSTMENT
    ];

    const DEDUCTIONS = [self::TYPE_STUDENT_CHARGE];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'candidate_transactions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['candidateId', 'amount', 'paymentType'], 'required'],
            [['candidateId', 'paymentType', 'chargeType', 'transaction_ref_id'], 'integer'],
            [['amount'], 'number'],
            [['candidateId', 'amount', 'paymentType', 'date_created', 'remarks', 'chargeType', 'transaction_ref_id', 'check_number', 'retest_crane_selection'], 'safe'],
            [['check_number'], 'string'],
            ['retest_crane_selection', 'required', 'when' => function($transaction) {
                return $transaction->paymentType == self::TYPE_STUDENT_CHARGE && $transaction->chargeType == self::SUBTYPE_PRACTICAL_RETEST;
            }],
            ['retest_crane_selection', 'default', 'value' => null],
            ['retest_crane_selection', 'in', 'range' => ['sw', 'fx', 'both']],
            [['transactionId', 'auth_code'], 'string', 'max' => 200]
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['date_created', 'date_updated'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['date_updated'],
                ],
                'value' => new \yii\db\Expression('NOW()'),
            ],
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
            'amount' => 'Amount',
            'transactionId' => 'Transaction ID',
            'auth_code' => 'Authorization Code',
            'transaction_ref_id' => 'Transaction Reference',
            'check_number' => 'Check Number',
            'retest_crane_selection' => 'Crane Selection (Practical Retest)',
            'date_created' => 'Date Created',
        ];
    }

    public function getPaymentTypeDesc()
    {
        if ($this->paymentType == self::TYPE_CASH) {
            return 'Payment Received - Cash';
        } else if($this->paymentType == self::TYPE_CHEQUE) {
            return 'Payment Received - Check';
        } else if($this->paymentType == self::TYPE_TRANSFER) {
            return 'Payment Transfer';
        } else if($this->paymentType == self::TYPE_PROMO) {
            return 'Promo';
        } else if($this->paymentType == self::TYPE_INTUIT) {
            return 'Payment Received - Intuit';
        } else if($this->paymentType == self::TYPE_ELECTRONIC_PAYMENT) {
            return 'Payment Received - Electronic Payment';
        } else if($this->paymentType == self::TYPE_RECEIVABLES_OTHER) {
            return 'Payment Received - Other';
        } else if($this->paymentType == self::TYPE_SQUARE) {
            return 'Payment Received - Square';
        } else if($this->paymentType == self::TYPE_DISCOUNT) {
            return 'Discount';
        } else if($this->paymentType == self::TYPE_ADJUSTMENT) {
            return 'Charge Adjustment';
        } else if($this->paymentType == self::TYPE_STUDENT_CHARGE) {
            if (strpos($this->remarks, 'Application Charge') !== false) {
                return 'Charge Added - Application';
            }
            if ($this->chargeType == self::SUBTYPE_NCCCO_OTHERS) {
                return 'Charge Added - NCCCO Other Fee';
            } else if($this->chargeType == self::SUBTYPE_PRACTICAL_RETEST) {
                return 'Charge Added - Practical Retest Fee';
            } else if($this->chargeType == self::SUBTYPE_WALK_IN_FEE) {
                return 'Charge Added - Walk-in Fee';
            } else if($this->chargeType == self::SUBTYPE_LATE_FEE) {
                return 'Charge Added - Late Fee';
            } else if($this->chargeType == self::SUBTYPE_CHANGE_FEE) {
                return 'Charge Added - Change Fee/Incomplete Application Fee';
            } else if($this->chargeType == self::SUBTYPE_ADD_PRACTICE_TIME) {
                return 'Charge Added - Additional Practice Time';
            }
            return 'Charge Added - Additional Charge (CSO)';
        } else if ($this->paymentType == self::TYPE_REFUND) {
            return 'Payment Refunded';
        }
    }

    public function getCandidate()
    {
        return $this->hasOne(Candidates::className(), ['id' => 'candidateId']);
    }
}
