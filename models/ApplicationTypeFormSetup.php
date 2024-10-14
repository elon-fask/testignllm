<?php
// TODO
namespace app\models;

use Yii;

/**
 * This is the model class for table "application_type_form_setup".
 *
 * @property integer $id
 * @property integer $application_type_id
 * @property string $form_name
 * @property string $form_setup
 * @property string $created_at
 *
 * @property ApplicationType $applicationType
 */
class ApplicationTypeFormSetup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'application_type_form_setup';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['application_type_id', 'form_name'], 'required'],
            [['application_type_id'], 'integer'],
            [['form_setup'], 'string'],
            [['created_at'], 'safe'],
            [['form_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'application_type_id' => 'Application Type ID',
            'form_name' => 'Form Name',
            'form_setup' => 'Form Setup',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationType()
    {
        return $this->hasOne(ApplicationType::className(), ['id' => 'application_type_id']);
    }

    public static function getFormTotal($appForm, $formName)
    {
        $priceTable = [
            'iai-blank-written-test-site-application-new-candidate' => [
                'W_FEE_LATE' => 50,
                'W_FEE_INCOMPLETE' => 30,
                'W_FEE_UPDATE_REPLACE' => 25,
                'W_FEE_CORE_1' => 165,
                'W_FEE_CORE_2' => 175,
                'W_FEE_CORE_3' => 185,
                'W_FEE_CORE_4' => 195,
                'W_FEE_ADDED_CORE' => 165,
                'W_FEE_ADDED_SPECIALTY_1' => 65,
                'W_FEE_ADDED_SPECIALTY_2' => 75,
                'W_FEE_ADDED_SPECIALTY_3' => 85,
                'W_FEE_ADDED_SPECIALTY_4' => 95,
                'W_FEE_TOWER_NEW' => 165,
                'W_FEE_TOWER_CURRENT' => 50,
                'W_FEE_OVERHEAD_NEW' => 165,
                'W_FEE_OVERHEAD_CURRENT' => 50
            ],
            'iai-blank-recert-with-1000-hours-application' => [
                'W_FEE_CORE_1' => 150,
                'W_FEE_CORE_2' => 155,
                'W_FEE_CORE_3' => 160,
                'W_FEE_CORE_4' => 165,
                'W_FEE_TOWER' => 150,
                'W_FEE_TOWER_W_MOBILE' => 50,
                'W_FEE_OVERHEAD' => 150,
                'W_FEE_OVERHEAD_W_MOBILE' => 50,
                'W_FEE_RETEST_CORE_1' => 150,
                'W_FEE_RETEST_SPECIALTY_1' => 50,
                'W_FEE_RETEST_SPECIALTY_2' => 55,
                'W_FEE_RETEST_SPECIALTY_3' => 60,
                'W_FEE_RETEST_SPECIALTY_4' => 65,
                'W_FEE_ADDED_SPECIALTY_1' => 65,
                'W_FEE_ADDED_SPECIALTY_2' => 75,
                'W_FEE_ADDED_SPECIALTY_3' => 85,
                'W_FEE_ADDED_TOWER' => 50,
                'W_FEE_ADDED_OVERHEAD' => 50,
                'W_FEE_LATE' => 50,
                'W_FEE_INCOMPLETE' => 30
            ]
        ];

        $result = 0;

        foreach ($appForm[$formName] as $field => $value) {
            if (isset($priceTable[$formName][$field]) && $priceTable[$formName][$field]) {
                $result += $priceTable[$formName][$field];
            }
        }

        return $result;
    }
}
