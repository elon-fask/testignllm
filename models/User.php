<?php

namespace app\models;

use Yii;
use app\models\UserOtp;
use app\helpers\UserHelper;
use app\helpers\UtilityHelper;

/**
 * This is the model class for table "user".
 *
 * @property integer $user_id
 * @property string $username
 * @property string $password
 * @property string $date_created
 * @property string $date_updated
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    const ROLE_ADMIN = 1;
    const ROLE_USER = 2;

    const TYPE_WRITTEN_ADMIN = 1;
    const TYPE_PRACTICAL_EXAMINER = 2;
    const TYPE_INSTRUCTOR = 3;
    const TYPE_TEST_COORDINATOR = 4;
    const TYPE_SITE_MANAGER = 5;
    const TYPE_LEAD_FIELD = 6;
    const TYPE_LEAD_CLASS = 7;
    const TYPE_ASST_FIELD = 8;
    const TYPE_ASST_CLASS = 9;
    const TYPE_BOOKKEEPER = 10;
    const TYPE_TRAVEL_COORDINATOR = 11;
    const TYPE_MARKETING_REP = 12;
    const TYPE_SALES_REP = 13;
    const TYPE_IT = 14;

    public $confirmPassword;
    public $confirmUsername;
    public $photoFile;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return User::findOne(['id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    public static function getStaffList()
    {
        return [
            self::TYPE_WRITTEN_ADMIN => self::getStaffTypeDescription(self::TYPE_WRITTEN_ADMIN),
            self::TYPE_PRACTICAL_EXAMINER => self::getStaffTypeDescription(self::TYPE_PRACTICAL_EXAMINER),
            self::TYPE_INSTRUCTOR => self::getStaffTypeDescription(self::TYPE_INSTRUCTOR),
            self::TYPE_TEST_COORDINATOR => self::getStaffTypeDescription(self::TYPE_TEST_COORDINATOR),
            self::TYPE_SITE_MANAGER => self::getStaffTypeDescription(self::TYPE_SITE_MANAGER),
            self::TYPE_LEAD_FIELD => self::getStaffTypeDescription(self::TYPE_LEAD_FIELD),
            self::TYPE_LEAD_CLASS => self::getStaffTypeDescription(self::TYPE_LEAD_CLASS),
            self::TYPE_ASST_FIELD => self::getStaffTypeDescription(self::TYPE_ASST_FIELD),
            self::TYPE_ASST_CLASS => self::getStaffTypeDescription(self::TYPE_ASST_CLASS),
            self::TYPE_BOOKKEEPER => self::getStaffTypeDescription(self::TYPE_BOOKKEEPER),
            self::TYPE_TRAVEL_COORDINATOR => self::getStaffTypeDescription(self::TYPE_TRAVEL_COORDINATOR),
            self::TYPE_MARKETING_REP => self::getStaffTypeDescription(self::TYPE_MARKETING_REP),
            self::TYPE_SALES_REP => self::getStaffTypeDescription(self::TYPE_SALES_REP),
            self::TYPE_IT => self::getStaffTypeDescription(self::TYPE_IT)
        ];
    }

    public static function getStaffTypeDescription($staffType)
    {
        if($staffType == self::TYPE_WRITTEN_ADMIN)
            return 'Written Admin';
        else if($staffType == self::TYPE_INSTRUCTOR)
            return 'Instructor' ;
        else if($staffType == self::TYPE_TEST_COORDINATOR)
            return 'Test Site Coordinator';
        else if($staffType == self::TYPE_SITE_MANAGER)
            return 'Site Manager';
        else if($staffType == self::TYPE_LEAD_FIELD)
            return 'Lead Field';
        else if($staffType == self::TYPE_LEAD_CLASS)
            return 'Lead Class';
        else if($staffType == self::TYPE_ASST_FIELD)
            return 'Assistant Field';
        else if($staffType == self::TYPE_ASST_CLASS)
            return 'Assistant Class';
        else if($staffType == self::TYPE_BOOKKEEPER)
            return 'Bookkeeper';
        else if($staffType == self::TYPE_TRAVEL_COORDINATOR)
            return 'Travel Coordinator';
        else if($staffType == self::TYPE_MARKETING_REP)
            return 'Marketing Rep';
        else if($staffType == self::TYPE_SALES_REP)
            return 'Sales Rep';
        else if($staffType == self::TYPE_IT)
            return 'IT/Tech';
        else
            return 'Practical Examiner';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'username', 'password', 'email'], 'required'],
            [['role', 'photo', 'active'], 'integer'],
            [['email'], 'email'],
            [['username'], 'unique'],
            [['role'], 'checkRole'],
            [['confirmPassword'], 'checkPassword'],
            ['confirmPassword', 'required', 'when' => function($model) {
                return $model->isNewRecord;
            }],
            [['homePhone', 'cellPhone', 'workPhone'], 'phoneNumber'],
            [['date_created', 'date_updated', 'staffType', 'fax', 'email'], 'safe'],
            [['first_name', 'last_name', 'username', 'password', 'homePhone','cellPhone', 'workPhone', 'city', 'state', 'zip', 'address1'], 'string', 'max' => 250]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name *',
            'email' => 'Email *',
            'last_name' => 'Last Name * ',
            'username' => 'Username *',
            'password' => 'Password *',
            'role' => 'Role',
            'homePhone' => 'Home Phone',
            'workPhone' => 'Work Phone',
            'cellPhone' => 'Cell Phone',
            'city' => 'City',
            'state' => 'State',
            'confirmUsername' => 'Confirm Email Address *',
            'confirmPassword' => 'Confirm Password *',
            'zip' => 'Zip',
            'address1' => 'Address',
            'photo' => 'Photo',
            'date_created' => 'Date Created',
            'date_updated' => 'Date Updated',
            'active' => 'Active',
        ];
    }

    public function extraFields()
    {
        return ['roles'];
    }

    public function beforeSave($insert)
    { 
        if (parent::beforeSave($insert)) {
            if ($this->password != '' && $this->password === $this->confirmPassword) {
                $this->password = \Yii::$app->getSecurity()->generatePasswordHash($this->password);
            }

            $this->date_updated = date('Y-m-d H:i:s', strtotime('now'));
            return true;
        } else {
            return false;
        }
    }

    public function checkPassword($attribute, $params)
    {
        if (($this->password != '' && $this->$attribute == '') || $this->$attribute != $this->password) {
            $this->addError($attribute, 'Password does not match');
        }
    }

    public function checkRole($attribute, $params)
    {
        if ($this->$attribute == '') {
            $this->addError($attribute, 'Type is required');
        }
    }

    public function checkEmail($attribute, $params)
    {
        if ($this->$attribute != $this->username) {
            $this->addError($attribute, 'Email does not match');
        }
    }

    public function phoneNumber($attribute,$params='')
    {
        if($this->$attribute != '' && strlen($this->$attribute) != 14)
        {
            $this->addError($attribute, 'Phone number is invalid, it should be in (858) 555-1212 format');
        }
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return "";
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return true;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        try {
            if (\Yii::$app->getSecurity()->validatePassword($password, $this->password)) {
                return true;
            }
            return false;
        } catch (\Exception $e) {
            if ($this->password === md5($password)) {
                $hash = \Yii::$app->getSecurity()->generatePasswordHash($password);
                $this->password = $hash;
                $this->save();
                return true;
            }
            return false;
        }
    }

    public function getRoles()
    {
        $userRoles = UserRole::findAll(['user_id' => $this->id]);
        $roles = array_map(function ($userRole) {
            return $userRole->role;
        }, $userRoles);

        return $roles;
    }

    public function getOtp()
    {
        return $this->hasMany(UserOtp::className(), ['user_id' => 'id']);
    }

    public function validateOtp($otp)
    {
        return $this->getOtp()->where(['otp_token' => $otp])->one();
    }

    public function getLinkedAccounts()
    {
        return $this->hasMany(UserOauth2Token::className(), ['user_id' => 'id']);
    }

    public function getImage(){
        if($this->photo == 1){
            return '/images/users/'.$this->id.'?t='.date('hhmmss', strtotime('now'));
        }
        return '/images/preview-default.png'; 
    }
    
    public function getFullName($showRole = true)
    {
        $suffix = '';

        if ($this->active == 0) {
            $suffix = ' - Archived';
        }

        return implode(' ', [$this->first_name, $this->last_name]);
    }

    public function getMessageFullName()
    {
        return $this->getFullName();
    }

    public function getStaffDescription(){
        return self::getStaffTypeDescription($this->staffType);
    }

    public function getSuggest($q) {
        $q = strtolower($q);
        
        $sql = "select * from user where role != ".self::ROLE_USER." and (LOWER(first_name) like ('%".$q."%') or LOWER(last_name) like ('%".$q."%') or LOWER(concat(first_name, ' ',last_name)) like ('%".$q."%') or LOWER(username) like ('%".$q."%'))";
        $command = \Yii::$app->db->createCommand($sql);
        $results = $command->queryAll();
        return $results;
    }

    public function updateRoles($roles) {
        UserRole::deleteAll('user_id = ' . $this->id);

        foreach ($roles as $role) {
            if (in_array($role, UserRole::ROLES)) {
                $userRole = new UserRole();
                $userRole->user_id = $this->id;
                $userRole->role = $role;
                $userRole->save();
            }
        }
    }

    public static function findByFullNameAndRole($fullName, $role)
    {
        $sql = <<<SQL
            SELECT user.* FROM user
            INNER JOIN user_role ON user.id = user_role.user_id
            WHERE CONCAT(user.first_name, ' ', user.last_name) = '{$fullName}'
            AND user_role.role = '{$role}'
SQL;
        return User::findBySql($sql);
    }

    public static function createUserFromName($fullName, $role)
    {
        $user = new User();
        $cleanedName = [];
        preg_match('/^\w+\ \w+/', $fullName, $cleanedName);
        $splitName = explode(' ', $cleanedName[0]);

        $user->first_name = $splitName[0];
        $user->last_name = $splitName[1] ?? $splitName[0];

        $user->username = $user->first_name[0] . $user->last_name;
        $user->email = 'admin@tabletbasedtesting.com';

        $password = \Yii::$app->getSecurity()->generateRandomString();

        $user->password = $password;
        $user->confirmPassword = $password;
        $user->active = 0;

        $user->save(false);

        $userRole = new UserRole();
        $userRole->user_id = $user->id;
        $userRole->role = $role;
        $userRole->save();

        return $user;
    }

    public static function findOrCreateUserFromNameAndRole($fullName, $role)
    {
        $user = User::findByFullNameAndRole($fullName, $role)->one();

        if (!isset($user)) {
            $user = User::createUserFromName($fullName, $role);
        }

        return $user;
    }
}
