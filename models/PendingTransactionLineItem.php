<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pending_transaction_line_item".
 *
 * @property int $id
 * @property int $tx_id
 * @property string $description
 * @property string $amount
 *
 * @property PendingTransaction $tx
 */
class PendingTransactionLineItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pending_transaction_line_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tx_id', 'description', 'amount'], 'required'],
            [['tx_id'], 'integer'],
            [['amount'], 'number'],
            [['description'], 'string', 'max' => 255],
            [['tx_id'], 'exist', 'skipOnError' => true, 'targetClass' => PendingTransaction::className(), 'targetAttribute' => ['tx_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tx_id' => 'Tx ID',
            'description' => 'Description',
            'amount' => 'Amount',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPendingTransaction()
    {
        return $this->hasOne(PendingTransaction::className(), ['id' => 'tx_id']);
    }
}
