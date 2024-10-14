<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "checklist".
 *
 * @property integer $id
 * @property integer $test_session_id
 * @property integer $template_id
 * @property string $name
 * @property integer $type
 * @property string $created_at
 * @property string $updated_at
 *
 * @property TestSession $testSession
 * @property ChecklistTemplate $template
 * @property ChecklistItem[] $checklistItems
 */
class Checklist extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'checklist';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['test_session_id', 'template_id', 'type'], 'integer'],
            [['name'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['test_session_id'], 'exist', 'skipOnError' => true, 'targetClass' => TestSession::className(), 'targetAttribute' => ['test_session_id' => 'id']],
            [['template_id'], 'exist', 'skipOnError' => true, 'targetClass' => ChecklistTemplate::className(), 'targetAttribute' => ['template_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'test_session_id' => 'Test Session ID',
            'template_id' => 'Template ID',
            'name' => 'Name',
            'type' => 'Type',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTestSession()
    {
        return $this->hasOne(TestSession::className(), ['id' => 'test_session_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplate()
    {
        return $this->hasOne(ChecklistTemplate::className(), ['id' => 'template_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChecklistItems()
    {
        return $this->hasMany(ChecklistItem::className(), ['checklist_id' => 'id']);
    }
}
