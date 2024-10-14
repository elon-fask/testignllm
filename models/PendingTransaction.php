<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "pending_transaction".
 *
 * @property int $id
 * @property int $posted_by
 * @property int $candidate_id
 * @property string $amount
 * @property int $type
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Candidates $candidate
 * @property User $postedBy
 */
class PendingTransaction extends \yii\db\ActiveRecord
{
    const PAYMENT_TYPES = [1, 2, 3, 4, 5, 6, 7];

    const TX_NAME_MAPPING = [
        1 => 'Cash',
        2 => 'Check',
        3 => 'Promo',
        4 => 'Authorize.Net',
        5 => 'Intuit Swiper',
        6 => 'Other',
        7 => 'Square'
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pending_transaction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['posted_by', 'candidate_id', 'amount', 'type'], 'required'],
            [['posted_by', 'candidate_id', 'type'], 'integer'],
            [['amount'], 'number'],
            [['posted_by', 'candidate_id', 'amount', 'type', 'created_at', 'updated_at'], 'safe'],
            [['candidate_id'], 'exist', 'skipOnError' => true, 'targetClass' => Candidates::className(), 'targetAttribute' => ['candidate_id' => 'id']],
            [['posted_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['posted_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'value' => new \yii\db\Expression('NOW()'),
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'posted_by' => 'Posted By',
            'candidate_id' => 'Candidate ID',
            'amount' => 'Amount',
            'type' => 'Type',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
    public function getPostedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'posted_by']);
    }

    public function getLineItems()
    {
        return $this->hasMany(PendingTransactionLineItem::className(), ['tx_id' => 'id']);
    }

    public function getLineItemsTotal()
    {
        $lineItems = $this->lineItems;

        return array_reduce($lineItems, function($acc, $lineItem) {
            return $lineItem->amount + $acc;
        }, 0);
    }
}
