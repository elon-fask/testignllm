<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "company_transaction".
 *
 * @property int $id
 * @property int $company_id
 * @property string $amount
 * @property string $type
 * @property string $transaction_id
 * @property string $auth_code
 * @property string $check_number
 * @property int $posted_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Company $company
 * @property User $postedBy
 * @property CompanyTransactionCandidateTransaction[] $companyTransactionCandidateTransactions
 */
class CompanyTransaction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company_transaction';
    }

    const TRANSACTION_TYPES = [
        'PAYMENT_CASH',
        'PAYMENT_CHECK',
        'PAYMENT_AUTHORIZE_NET',
        'PAYMENT_SQUARE',
        'PAYMENT_INTUIT',
        'PAYMENT_OTHER'
    ];

    const CANDIDATE_PAYMENT_TYPE_MAPPING = [
        'PAYMENT_CASH' => CandidateTransactions::TYPE_CASH,
        'PAYMENT_CHECK' => CandidateTransactions::TYPE_CHEQUE,
        'PAYMENT_AUTHORIZE_NET' => CandidateTransactions::TYPE_ELECTRONIC_PAYMENT,
        'PAYMENT_INTUIT' => CandidateTransactions::TYPE_INTUIT,
        'PAYMENT_SQUARE' => CandidateTransactions::TYPE_SQUARE,
        'PAYMENT_OTHER' => CandidateTransactions::TYPE_RECEIVABLES_OTHER
    ];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company_id', 'amount', 'type', 'posted_by'], 'required'],
            [['company_id', 'posted_by'], 'integer'],
            [['amount'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['type', 'transaction_id', 'auth_code', 'check_number'], 'string', 'max' => 255],
            ['type', 'in', 'range' => self::TRANSACTION_TYPES],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::className(), 'targetAttribute' => ['company_id' => 'id']],
            [['posted_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['posted_by' => 'id']],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
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
            'company_id' => 'Company ID',
            'amount' => 'Amount',
            'type' => 'Type',
            'transaction_id' => 'Transaction ID',
            'auth_code' => 'Auth Code',
            'check_number' => 'Check Number',
            'posted_by' => 'Posted By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'posted_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyTransactionCandidateTransactions()
    {
        return $this->hasMany(CompanyTransactionCandidateTransaction::className(), ['company_transaction_id' => 'id']);
    }

    public function getCandidateTransactions()
    {
        return $this->hasMany(CandidateTransactions::className(), ['id' => 'candidate_transaction_id'])->via('companyTransactionCandidateTransactions');
    }
}
