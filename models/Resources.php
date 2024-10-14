<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "resources".
 *
 * @property integer $id
 * @property integer $type
 * @property string $name
 * @property string $notes
 * @property string $created_at
 */
class Resources extends \yii\db\ActiveRecord
{
    const TYPE_CRANE = 1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'resources';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'name'], 'required'],
            [['type'], 'integer'],
            [['created_at'], 'safe'],
            [['name'], 'string', 'max' => 256],
            [['notes'], 'string', 'max' => 2500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'name' => 'Name',
            'notes' => 'Notes',
            'created_at' => 'Created At',
        ];
    }
    public function getTypeDescription(){
        foreach(Resources::getTypes() as $key => $type){
            if($this->type == $key){
                return $type;
            }
        }
        return '';
    }
    public static function getTypes(){
        $types = array();
        $types[self::TYPE_CRANE] = 'Crane';
        return $types;
    }
}
