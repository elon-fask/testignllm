<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "test_site_service".
 *
 * @property integer $id
 * @property integer $test_site_id
 * @property integer $application_type_id
 * @property string $date_created
 * @property string $date_updated
 *
 * @property ApplicationType $applicationType
 * @property TestSite $testSite
 */
class TestSiteService extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'test_site_service';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['test_site_id', 'application_type_id'], 'required'],
            [['test_site_id', 'application_type_id'], 'integer'],
            [['date_created', 'date_updated'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'test_site_id' => 'Test Site ID',
            'application_type_id' => 'Application Type ID',
            'date_created' => 'Date Created',
            'date_updated' => 'Date Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationType()
    {
        return $this->hasOne(ApplicationType::className(), ['id' => 'application_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTestSite()
    {
        return $this->hasOne(TestSite::className(), ['id' => 'test_site_id']);
    }
}
