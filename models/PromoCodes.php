<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "promo_codes".
 *
 * @property integer $id
 * @property string $code
 * @property double $discount
 * @property string $assignedToName
 * @property string $date_created
 * @property string $date_updated
 */
class PromoCodes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'promo_codes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'discount', 'assignedToName'], 'required'],
            [['discount'], 'number', 'min' => 0],
            [['code'], 'unique'],
            [['date_created','isPurchaseOrder', 'date_updated', 'archived'], 'safe'],
            [['code', 'assignedToName'], 'string', 'max' => 255],
            [['code', 'assignedToName'], function ($attribute) {
                $this->$attribute = \yii\helpers\HtmlPurifier::process($this->$attribute);
            }],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'discount' => 'Discount',
            'assignedToName' => 'Assigned To Name',
            'date_created' => 'Date Created',
            'date_updated' => 'Date Updated',
            'isPurchaseOrder' => 'This promo code is associated with a purchase order (bypass the credit card transaction)'
        ];
    }
}
