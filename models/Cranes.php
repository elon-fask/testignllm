<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cranes".
 *
 * @property integer $id
 * @property string $model
 * @property string $manufacturer
 * @property string $unitNum
 * @property string $serialNum
 * @property integer $cad
 * @property integer $weightCerts
 * @property integer $loadChart
 * @property integer $manual
 * @property integer $certificate
 * @property string $certificateExpirateDate
 * @property string $companyOwner
 * @property integer $preChecklistId
 * @property integer $postChecklistId
 * @property string $date_created
 * @property integer $isDeleted
 */
class Cranes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cranes';
    }

    public static function getFilesForUpload(){
        return ['cad',
            'weightCerts',
            'loadChart',
            'manual',
            'certificate'];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model', 'manufacturer', 'unitNum', 'serialNum'], 'required'],
            [['preChecklistId', 'postChecklistId', 'isDeleted'], 'integer'],
            [['testSiteId', 'date_created','cadFilename','weightCertsFilename','loadChartFilename','manualFilename','certificateFilename'], 'safe'],
            [['model', 'manufacturer', 'unitNum', 'serialNum', 'companyOwner'], 'string', 'max' => 250],
            [['certificateExpirateDate'], 'string', 'max' => 25],
        ];
    }


    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'model' => 'Model',
            'manufacturer' => 'Manufacturer',
            'unitNum' => 'Unit Num',
            'serialNum' => 'Serial Num',
            'cad' => 'Cad',
            'weightCerts' => 'Weight Certs',
            'loadChart' => 'Load Chart',
            'manual' => 'Manual',
            'certificate' => 'Certificate',
            'certificateExpirateDate' => 'Certificate Expirate Date',
            'companyOwner' => 'Company Owner',
            'preChecklistId' => 'Pre ChecklistTemplate ID',
            'postChecklistId' => 'Post ChecklistTemplate ID',
            'date_created' => 'Date Created',
            'isDeleted' => 'Is Deleted',
        ];
    }
    
    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            $this->date_created=date('Y-m-d H:i:s', strtotime('now'));
            return true;
        }else{
            return false;
        }
    }
    
    public static function getAvailableManufacturer(){
        return ['Acme' => 'Acme',
'Air Technical Industries' => 'Air Technical Industries',
'Altec' => 'Altec',
'American' => 'American',
'Arva' => 'Arva',
'Ascom' => 'Ascom',
'Atlas' => 'Atlas',
'Austin-Western' => 'Austin-Western',
'Auto Crane' => 'Auto Crane',
'Badger' => 'Badger',
'Bantam' => 'Bantam',
'Benazzato' => 'Benazzato',
'Broderson' => 'Broderson',
'Bucyrus-Erie' => 'Bucyrus-Erie',
'Case' => 'Case',
'Caterpillar' => 'Caterpillar',
'Clark-Lima' => 'Clark-Lima',
'Cobra' => 'Cobra',
'Comansa' => 'Comansa',
'Copma' => 'Copma',
'Cormach' => 'Cormach',
'Demag' => 'Demag',
'Dresser - Galion' => 'Dresser - Galion',
'Drott' => 'Drott',
'Dur-A-Lift' => 'Dur-A-Lift',
'Dynalift' => 'Dynalift',
'Ederer' => 'Ederer',
'Effer' => 'Effer',
'Elliott' => 'Elliott',
'Epsilon' => 'Epsilon',
'ETI' => 'ETI',
'Fassi' => 'Fassi',
'Favelle Favco' => 'Favelle Favco',
'Ferrari' => 'Ferrari',
'Fuchs' => 'Fuchs',
'Fushun' => 'Fushun',
'Galion' => 'Galion',
'Garland' => 'Garland',
'Gci' => 'Gci',
'Giuffre Brothers' => 'Giuffre Brothers',
'Gottwald' => 'Gottwald',
'Grove' => 'Grove',
'Guerra' => 'Guerra',
'Hanson' => 'Hanson',
'Heila' => 'Heila',
'Hiab' => 'Hiab',
'Hi-Ranger' => 'Hi-Ranger',
'Hitachi' => 'Hitachi',
'Hitachi / Sumitomo' => 'Hitachi / Sumitomo',
'Hyco' => 'Hyco',
'Hyster' => 'Hyster',
'Hyundai' => 'Hyundai',
'IHI' => 'IHI',
'Imt' => 'Imt',
'Jaso' => 'Jaso',
'Jekko' => 'Jekko',
'Jlg' => 'Jlg',
'Kato' => 'Kato',
'Kobelco' => 'Kobelco',
'Koehring' => 'Koehring',
'Koenig' => 'Koenig',
'Komatsu' => 'Komatsu',
'Liebherr' => 'Liebherr',
'Link-Belt' => 'Link-Belt',
'Manitex' => 'Manitex',
'Manitowoc' => 'Manitowoc',
'National' => 'National',
'Tadano' => 'Tadano',
'Terex' => 'Terex'];
    }
    
    public function getCadFile(){
        if($this->cad == 1){
            return '/cranes/'.md5($this->id).'/'.$this->cadFilename;
        }   
        return '';
    }
    
    public function getWeightCertsFile(){
        if($this->weightCerts == 1){
            return '/cranes/'.md5($this->id).'/'.$this->weightCertsFilename;
        }
        return '';
    }
    public function getLoadChartFile(){
        if($this->loadChart == 1){
            return '/cranes/'.md5($this->id).'/'.$this->loadChartFilename;
        }
        return '';
    }
    public function getManualFile(){
        if($this->manual == 1){
            return '/cranes/'.md5($this->id).'/'.$this->manualFilename;
        }
        return '';
    }
    public function getCertificateFile(){
        if($this->certificate == 1){
            return '/cranes/'.md5($this->id).'/'.$this->certificateFilename;
        }
        return '';
    }
    
    public function getDescription(){
        return $this->manufacturer.' - '.$this->model.' ('.$this->serialNum.')';   
    }
}
