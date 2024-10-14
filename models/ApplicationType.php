<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "application_type".
 *
 * @property integer $id
 * @property string $name
 * @property string $keyword
 * @property string $description
 * @property double $price
 * @property double $iaiFee
 * @property double $lateFee
 * @property integer $isArchived
 * @property double $practicalCharge
 * @property string $date_created
 * @property string $date_updated
 */
class ApplicationType extends \yii\db\ActiveRecord
{
    const TYPE_PUBLIC = 1;
    const TYPE_PRIVATE = 2;

    public static function getAppTypes()
    {
        $types = array();
        $types[self::TYPE_PRIVATE] = 'Private';
        $types[self::TYPE_PUBLIC] = 'Public';

        return $types;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'application_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'keyword', 'app_type', 'price'], 'required'],
            [['keyword'], 'unique'],
            [['price', 'iaiFee', 'lateFee', 'app_type'], 'number'],
            [['cross_out_cc_fields', 'isArchived'], 'boolean'],
            [['cross_out_cc_fields', 'date_created', 'date_updated', 'infoText', 'isRecertify'], 'safe'],
            [['name', 'keyword', 'description'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'keyword' => 'Keyword',
            'description' => 'Description',
            'price' => 'Price',
            'iaiFee' => 'NCCCO Testing Services Fee',
            'lateFee' => 'Late Fee',
            'app_type' => 'Public/Private Setting',
            'isArchived' => 'Archived',
            'cross_out_cc_fields' => 'Cross Out Credit Card Fields',
            'date_created' => 'Date Created',
            'date_updated' => 'Date Updated'
        ];
    }

    public function extraFields()
    {
        return [
            'applicationForms' => function() {
                return $this->getApplicationFormSetups()->all();
            }
        ];
    }

    public function getApplicationFormSetups()
    {
        return $this->hasMany(ApplicationTypeFormSetup::className(), ['application_type_id' => 'id']);
    }

    public function getIsPracticalOnly()
    {
        if ($this->name === 'Test') {
            return false;
        }

        $writtenForms = ['iai-blank-written-test-site-application-new-candidate', 'iai-blank-recert-with-1000-hours-application'];
        return array_reduce($this->applicationFormSetups, function($acc, $appForm) use ($writtenForms) {
            return $acc && !in_array($appForm->form_name, $writtenForms);
        }, true);
    }

    public function getNumCranes()
    {
        $practicalForm = 'iai-blank-practical-test-application-form';
        return array_reduce($this->applicationFormSetups, function($acc, $appForm) use ($practicalForm) {
            if ($appForm->form_name == $practicalForm) {
                $formSetup = json_decode($appForm->form_setup);
                $craneCount = 0;
                foreach ($formSetup as $field => $val) {
                    if ($field == 'P_TELESCOPIC_TLL' || $field == 'P_TELESCOPIC_TSS') {
                        $craneCount += 1;
                    }
                }
                return $acc + $craneCount;
            }
            return $acc;
        }, 0);
    }

    public function getCranes()
    {
        $practicalForm = 'iai-blank-practical-test-application-form';
        return array_reduce($this->applicationFormSetups, function($acc, $appForm) use ($practicalForm) {
            if ($appForm->form_name == $practicalForm) {
                $formSetup = json_decode($appForm->form_setup, true);
                $hasSW = isset($formSetup['P_TELESCOPIC_TLL']) && $formSetup['P_TELESCOPIC_TLL'];
                $hasFX = isset($formSetup['P_TELESCOPIC_TSS']) && $formSetup['P_TELESCOPIC_TSS'];

                if ($hasSW && $hasFX) {
                    return 'both';
                }

                if ($hasSW) {
                    return 'sw';
                }

                if ($hasFX) {
                    return 'fx';
                }

                return 'none';
            }
            return $acc;
        }, 0);
    }

    public function hasRecertForm()
    {
        return count($this->getApplicationFormSetups()->where([
            'form_name' => 'iai-blank-recert-with-1000-hours-application'
            ])->andWhere(['<>', 'form_setup', '[]'])->all()) > 0;
    }

    public function archive()
    {
        $this->isArchived = true;
        $this->save(false);
    }

    public function unarchive()
    {
        $this->isArchived = false;
        $this->save(false);
    }
}
