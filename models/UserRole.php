<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_role".
 *
 * @property int $id
 * @property int $user_id
 * @property string $role
 *
 * @property User $user
 */
class UserRole extends \yii\db\ActiveRecord
{
    const SUPER_ADMIN = 'SUPER_ADMIN';
    const WRITTEN_ADMIN = 'WRITTEN_ADMIN';
    const PRACTICAL_EXAMINER = 'PRACTICAL_EXAMINER';
    const INSTRUCTOR = 'INSTRUCTOR';
    const TEST_SITE_COORDINATOR = 'TEST_SITE_COORDINATOR';
    const SITE_MANAGER = 'SITE_MANAGER';
    const LEAD_FIELD = 'LEAD_FIELD';
    const LEAD_CLASS = 'LEAD_CLASS';
    const ASSISTANT_FIELD = 'ASSISTANT_FIELD';
    const ASSISTANT_CLASS = 'ASSISTANT_CLASS';
    const BOOKKEEPER = 'BOOKKEEPER';
    const TRAVEL_COORDINATOR = 'TRAVEL_COORDINATOR';
    const MARKETING_REP = 'MARKETING_REP';
    const SALES_REP = 'SALES_REP';
    const IT_TECH = 'IT_TECH';
    const PROCTOR = 'PROCTOR';

    const STAFF_TYPE_MAPPING = [
        0 => self::SUPER_ADMIN,
        1 => self::WRITTEN_ADMIN,
        2 => self::PRACTICAL_EXAMINER,
        3 => self::INSTRUCTOR,
        4 => self::TEST_SITE_COORDINATOR,
        5 => self::SITE_MANAGER,
        6 => self::LEAD_FIELD,
        7 => self::LEAD_CLASS,
        8 => self::ASSISTANT_FIELD,
        9 => self::ASSISTANT_CLASS,
        10 => self::BOOKKEEPER,
        11 => self::TRAVEL_COORDINATOR,
        12 => self::MARKETING_REP,
        13 => self::SALES_REP,
        14 => self::IT_TECH,
        15 => self::PROCTOR
    ];

    const ROLES = [
        self::SUPER_ADMIN,
        self::WRITTEN_ADMIN,
        self::PRACTICAL_EXAMINER,
        self::INSTRUCTOR,
        self::PROCTOR,
        self::TEST_SITE_COORDINATOR,
        self::SITE_MANAGER,
        self::LEAD_FIELD,
        self::LEAD_CLASS,
        self::ASSISTANT_FIELD,
        self::ASSISTANT_CLASS,
        self::BOOKKEEPER,
        self::TRAVEL_COORDINATOR,
        self::MARKETING_REP,
        self::SALES_REP,
        self::IT_TECH
    ];

    const ROLES_DESC = [
        self::SUPER_ADMIN => 'Website Admin',
        self::WRITTEN_ADMIN => 'Written Admin',
        self::PRACTICAL_EXAMINER => 'Practical Examiner',
        self::INSTRUCTOR => 'Instructor',
        self::TEST_SITE_COORDINATOR => 'Test Site Coordinator',
        self::SITE_MANAGER => 'Site Manager',
        self::LEAD_FIELD => 'Lead Field',
        self::LEAD_CLASS => 'Lead Class',
        self::ASSISTANT_FIELD => 'Assistant Field',
        self::ASSISTANT_CLASS => 'Assistant Class',
        self::BOOKKEEPER => 'Bookkeeper',
        self::TRAVEL_COORDINATOR => 'Travel Coordinator',
        self::MARKETING_REP => 'Marketing Rep',
        self::SALES_REP => 'Sales Rep',
        self::IT_TECH => 'IT/Tech',
        self::PROCTOR => 'Proctor'
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_role';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'role'], 'required'],
            [['user_id'], 'integer'],
            [['role'], 'string', 'max' => 255],
            ['role', 'in', 'range' => array_keys(self::ROLES_DESC)],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'role' => 'Role',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
