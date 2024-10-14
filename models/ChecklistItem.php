<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "checklist_item".
 *
 * @property integer $id
 * @property integer $checklist_id
 * @property integer $item_type
 * @property string $name
 * @property string $description
 * @property integer $value
 * @property integer $failing_score
 * @property string $note
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Checklist $checklist
 */
class ChecklistItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'checklist_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['checklist_id', 'name', 'failing_score'], 'required'],
            [['checklist_id', 'item_type', 'value', 'failing_score'], 'integer'],
            [['description', 'note'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['checklist_id'], 'exist', 'skipOnError' => true, 'targetClass' => Checklist::className(), 'targetAttribute' => ['checklist_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'checklist_id' => 'Checklist ID',
            'item_type' => 'Item Type',
            'name' => 'Name',
            'description' => 'Description',
            'value' => 'Value',
            'failing_score' => 'Failing Score',
            'note' => 'Note',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChecklist()
    {
        return $this->hasOne(Checklist::className(), ['id' => 'checklist_id']);
    }
}
