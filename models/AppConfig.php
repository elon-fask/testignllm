<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "app_config".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $val
 * @property string $date_created
 * @property string $date_updated
 */
class AppConfig extends \yii\db\ActiveRecord
{
    const IAI_1_PRACTICAL_CRANE = 'IAI_1_PRACTICAL_CRANE';
    const IAI_2_PRACTICAL_CRANE = 'IAI_2_PRACTICAL_CRANE';
    const IAI_FEE_LESS_12 = 'IAI_FEE_LESS_12';
    const IAI_FEE_LESS_15 = 'IAI_FEE_LESS_15';
    const UNFINISHED_REGISTRATION_EMAIL_RECIPIENT = 'UNFINISHED_REGISTRATION_EMAIL_RECIPIENT';
    const NEW_CANDIDATES_CCS_EMAIL_RECIPIENT = 'NEW_CANDIDATES_CCS_EMAIL_RECIPIENT';
    const NEW_CANDIDATES_ACS_EMAIL_RECIPIENT = 'NEW_CANDIDATES_ACS_EMAIL_RECIPIENT';
    const TRAVEL_FORM_EMAIL_RECIPIENT = 'TRAVEL_FORM_EMAIL_RECIPIENT';
    const UPCOMING_CLASS_REPORT_EMAIL_RECIPIENT = 'UPCOMING_CLASS_REPORT_EMAIL_RECIPIENT';
    const PIPEDRIVE_API_KEY = 'PIPEDRIVE_API_KEY';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'app_config';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'name', 'val'], 'required'],
            [['date_created', 'date_updated'], 'safe'],
            [['code', 'name', 'val'], 'string', 'max' => 255]
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
            'name' => 'Name',
            'val' => 'Val',
            'date_created' => 'Date Created',
            'date_updated' => 'Date Updated',
        ];
    }

    public function getInputOptions()
    {
        $textFields = [
            self::UNFINISHED_REGISTRATION_EMAIL_RECIPIENT,
            self::NEW_CANDIDATES_CCS_EMAIL_RECIPIENT,
            self::NEW_CANDIDATES_ACS_EMAIL_RECIPIENT,
            self::TRAVEL_FORM_EMAIL_RECIPIENT,
            self::UPCOMING_CLASS_REPORT_EMAIL_RECIPIENT,
            self::PIPEDRIVE_API_KEY,
        ];

        if (in_array($this->code, $textFields)) {
            return ['type' => 'text', 'width' => ''];
        }

        return ['type' => 'number', 'width' => 'width: 85px;'];
    }
}
