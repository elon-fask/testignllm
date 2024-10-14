<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "staff".
 *
 * @property integer $id
 * @property string $firstName
 * @property string $lastName
 * @property integer $staffType
 * @property string $date_created
 * @property string $date_updated
 */
/**
 * 
 * 
 * 
 * 
 * 
 * DEPRECATED, DO NOT USE, USE USER
 * 
 * 
 * 
 * 
 * @author DJ
 *
 */
class Staff extends \yii\db\ActiveRecord
{
    const TYPE_WRITTEN_ADMIN = 1;
    const TYPE_PRACTICAL_EXAMINER = 2;
    const TYPE_INSTRUCTOR = 3;
    const TYPE_TEST_COORDINATOR = 4;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'staff';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['firstName', 'lastName', 'staffType'], 'required'],
            [['staffType'], 'integer'],
            [['email'], 'email'],
            [['date_created', 'date_updated', 'phone', 'fax', 'email', 'archived'], 'safe'],
            [['firstName', 'lastName'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'firstName' => 'First Name',
            'lastName' => 'Last Name',
            'staffType' => 'Staff Type',
            'date_created' => 'Date Created',
            'date_updated' => 'Date Updated',
        ];
    }
    
    public function getFullName(){
        return $this->firstName .' '.$this->lastName;
    }
    
    public function getStaffTypeDescription(){
            if($this->staffType == self::TYPE_WRITTEN_ADMIN)
                return 'Written Admin';
            else if($this->staffType == self::TYPE_INSTRUCTOR)
                return 'Instructor' ;
            else if($this->staffType == self::TYPE_TEST_COORDINATOR)
                return 'Test Site Coordinator';
            else
                return 'Practical Examiner';
       
    }
}
