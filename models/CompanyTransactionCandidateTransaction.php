<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "company_transaction_candidate_transaction".
 *
 * @property int $id
 * @property int $company_transaction_id
 * @property int $candidate_transaction_id
 *
 * @property CandidateTransactions $candidateTransaction
 * @property CompanyTransaction $companyTransaction
 */
class CompanyTransactionCandidateTransaction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company_transaction_candidate_transaction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company_transaction_id', 'candidate_transaction_id'], 'required'],
            [['company_transaction_id', 'candidate_transaction_id'], 'integer'],
            [['candidate_transaction_id'], 'exist', 'skipOnError' => true, 'targetClass' => CandidateTransactions::className(), 'targetAttribute' => ['candidate_transaction_id' => 'id']],
            [['company_transaction_id'], 'exist', 'skipOnError' => true, 'targetClass' => CompanyTransaction::className(), 'targetAttribute' => ['company_transaction_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_transaction_id' => 'Company Transaction ID',
            'candidate_transaction_id' => 'Candidate Transaction ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCandidateTransaction()
    {
        return $this->hasOne(CandidateTransactions::className(), ['id' => 'candidate_transaction_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyTransaction()
    {
        return $this->hasOne(CompanyTransaction::className(), ['id' => 'company_transaction_id']);
    }
}
