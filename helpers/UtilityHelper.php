<?php

namespace app\helpers;

use app\models\TestSite;
use app\models\ApplicationType;
use app\models\Staff;
use app\models\Candidates;
use app\models\CandidateSession;
use app\models\TestSession;
use app\models\ApplicationTypeFormSetup;
use mikehaertl\pdftk\Pdf;
use yii\helpers\Html;
use app\models\AppConfig;
use app\models\LastInstructor;
use app\models\CandidateTransactions;
use app\models\User;
use app\models\UserRole;
use app\models\CandidateSessionExamPhoto;

class UtilityHelper {

    static public function gen_uuid() {

        $s = strtoupper(md5(uniqid(rand(),true)));
        $guidText =
            substr($s,0,8) . '-' .
            substr($s,8,4) . '-' .
            substr($s,20);
        return $guidText;
    }

    static public function getAcsCcsUrl($school){
        return \Yii::$app->params[strtolower($school).'.url'];
    }

    static public function generateUniqueCodeForTestSite() {
        $code = self::gen_uuid();
        $continue = true;
        do{
            $testSite = TestSite::findOne(['uniqueCode' => $code]);
            if($testSite == null){
                return $code;
            }
        }while($continue);
    }


    static public function getSubdomain(){
        preg_match('/(?:http[s]*\:\/\/)*(.*?)\.(?=[^\/]*\..{2,5})/i', $_SERVER['HTTP_HOST'], $match);
        if(isset($match[1])){
            if( stripos($match[1], 'acs') !== false){
                return 'acs';
            }
            else if( stripos($match[1], 'ccs') !== false){
                return 'ccs';
            }
        }
        return '';
    }
    public static function curPageURL() {
        $pageURL = 'http';
        if (isset($_SERVER["HTTPS"] ) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"];
        }
        return $pageURL;
    }
    static public function cleanInputs(){
        $data = array(
            'GET'    => &$_GET,
            'POST'   => &$_POST,
            'COOKIE' => &$_COOKIE,
            'FILES'  => &$_FILES
        );

        $clean = 'GET,POST,COOKIE,FILES';




        $dataForClean = explode(',',$clean);
        if(count($dataForClean))
        {
            foreach ($dataForClean as $key => $value)
            {
                if(isset ($data[$value]) && count($data[$value]))
                {
                    self::doXssClean($data[$value]);
                }
            }
        }
    }


    static public function doXssClean(&$data)
    {
        if(is_array($data) && count($data))
        {
            foreach($data as $k => $v)
            {
                $data[$k] = self::doXssClean($v);
            }
            return $data;
        }

        if(trim($data) === '')
        {
            return $data;
        }

        $tags = 'STRICT';
        switch ($tags)
        {
            case 'STRICT':
                $data = strip_tags($data);
                break;
            case 'SOFT':
                $data = htmlentities($data,ENT_QUOTES,'UTF-8');
                break;
            case 'NONE':
                break;
            // по умолчанию - strict
            default:
                $data = strip_tags($data);
        }

        // xss_clean function from Kohana framework 2.3.4
        $data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
        $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
        $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
        $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');
        // Remove any attribute starting with "on" or xmlns
        $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);
        // Remove javascript: and vbscript: protocols
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);
        // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);
        // Remove namespaced elements (we do not need them)
        $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);
        do
        {
            // Remove really unwanted tags
            $old_data = $data;
            $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
        }
        while ($old_data !== $data);
        return $data;
    }
    static public function dateconvert($date,$func) {
        if($date != ''){
            if ($func == 1){ //insert conversion
                //list($month, $day, $year) = split('[/.-]', $date);

                $dateInfos = explode('/', $date);
                $month = $dateInfos[0];
                $day = $dateInfos[1];
                $year = $dateInfos[2];

                $date = "$year-$month-$day";
                return $date;
            }
            if ($func == 2){ //output conversion
                $dates = explode(' ', $date);
                //list($year, $month, $day) =  split('[-.]', $dates[0]);

                $dateInfos = explode('-', $dates[0]);
                $year = $dateInfos[0];
                $month = $dateInfos[1];
                $day = $dateInfos[2];

                $date = "$month/$day/$year";
                return $date;
            }
        }
        return '';
    }



    public static function getTestSites($type){
        $testSites = TestSite::find()->all();
        $types = array();
        foreach($testSites as $site){
            if($site->type == $type){
                $types[$site->id] = $site->getTestSiteName();
            }
        }
        return $types;
    }
    public static function getStaff($type){
        $staffs = User::findAll(['staffType' => $type]);
        $resp = array();
        foreach($staffs as $staff){
            if($staff->active == 1)
                $resp[$staff->id] = $staff->getFullName();
        }
        return $resp;
    }
    public static function getEnrollmentTypes(){
        $types = array();
        $types[TestSite::ENROLLMENT_TYPE_PRIVATE] = 'Private Enrollment';
        $types[TestSite::ENROLLMENT_TYPE_PUBLIC] = 'Public Enrollment';
        return $types;
    }
    public static function getEnrollmentTypesShort(){
        $types = array();
        $types[TestSite::ENROLLMENT_TYPE_PRIVATE] = 'Private';
        $types[TestSite::ENROLLMENT_TYPE_PUBLIC] = 'Public';
        return $types;
    }
    public static function getScheduleTypes(){
        $types = array();
        $types[TestSite::SCHEDULE_TYPE_CLOSED] = 'Closed for Schedule';
        $types[TestSite::SCHEDULE_TYPE_OPENED] = 'Opened for Schedule';
        return $types;
    }
    public static function getScheduleTypesShort(){
        $types = array();
        $types[TestSite::SCHEDULE_TYPE_CLOSED] = 'Closed';
        $types[TestSite::SCHEDULE_TYPE_OPENED] = 'Opened';
        return $types;
    }
    public static function getAllTestSites($d = null){

        $testSites = TestSite::find()->all();
        $data = array();
        /*wroten from me*/
        if(!empty($d)){
            foreach($testSites as $site){
                $data[$site->id] = $site->name.' ('. $site->getTestSiteLocation($d).')';
            }
        }else{
            foreach($testSites as $site){
                $data[$site->id] = $site->name.' ('. $site->getTestSiteLocation().')';
            }
        }

        return $data;
    }
    public static function getApplicationTypes(){
        $appTypes = ApplicationType::find()->all();
        $data = array();
        foreach($appTypes as $appType){
            $data[$appType->id] = $appType->name;
        }
        return $data;
    }
    static public function surveyOptions(){
        return ['Ad (Online)'=>'Ad (Online)',
            'Flyer'=>'Flyer',
            'Heard from a friend'=>'Heard from a friend',
            'Other'=>'Other',
        ];
    }
    static public function StateList()
    {
        return array(
            "AK"=>"AK",
            "AL"=>"AL",
            "AR"=>"AR",
            "AZ"=>"AZ",
            "CA"=>"CA",
            "CO"=>"CO",
            "CT"=>"CT",
            "DC"=>"DC",
            "DE"=>"DE",
            "FL"=>"FL",
            "GA"=>"GA",
            "HI"=>"HI",
            "IA"=>"IA",
            "ID"=>"ID",
            "IL"=>"IL",
            "IN"=>"IN",
            "KS"=>"KS",
            "KY"=>"KY",
            "LA"=>"LA",
            "MA"=>"MA",
            "MD"=>"MD",
            "ME"=>"ME",
            "MI"=>"MI",
            "MN"=>"MN",
            "MO"=>"MO",
            "MS"=>"MS",
            "MT"=>"MT",
            "NC"=>"NC",
            "ND"=>"ND",
            "NE"=>"NE",
            "NH"=>"NH",
            "NJ"=>"NJ",
            "NM"=>"NM",
            "NV"=>"NV",
            "NY"=>"NY",
            "OH"=>"OH",
            "OK"=>"OK",
            "OR"=>"OR",
            "PA"=>"PA",
            "RI"=>"RI",
            "SC"=>"SC",
            "SD"=>"SD",
            "TN"=>"TN",
            "TX"=>"TX",
            "UT"=>"UT",
            "VA"=>"VA",
            "VT"=>"VT",
            "WA"=>"WA",
            "WI"=>"WI",
            "WV"=>"WV",
            "WY"=>"WY"
        );
    }
    public static function jb_verbose_date_range($start_date = '',$end_date = '') {

        $date_range = '';
        if(date('F j, Y',$start_date) == date('F j, Y',$end_date)){
            return  date( 'F j, Y', $start_date );
        }else if(date('Y',$start_date) == date('Y',$end_date)){
            if(date('F',$start_date) == date('F',$end_date)){
                return  date( 'F j', $start_date ).' - '.date( 'j, Y', $end_date );
            }else{
                return  date( 'F j', $start_date ).' - '.date( 'F j, Y', $end_date );
            }
        }else{
            return  date( 'F j, Y', $start_date ).' - '.date( 'F j, Y', $end_date );
        }

    }
    public static function jb_verbose_date_range_old($start_date = '',$end_date = '') {

        $date_range = '';

        // If only one date, or dates are the same set to FULL verbose date
        if ( empty($start_date) || empty($end_date) || ( date('FjY',$start_date) == date('FjY',$end_date) ) ) { // FjY == accounts for same day, different time
            $start_date_pretty = date( 'F jS, Y', $start_date );
            $end_date_pretty = date( 'F jS, Y', $end_date );
        } else {
            // Setup basic dates
            $start_date_pretty = date( 'F j', $start_date );
            $end_date_pretty = date( 'jS, Y', $end_date );
            // If years differ add suffix and year to start_date
            if ( date('Y',$start_date) != date('Y',$end_date) ) {
                $start_date_pretty .= date( 'S, Y', $start_date );
            }

            // If months differ add suffix and year to end_date
            if ( date('F',$start_date) != date('F',$end_date) ) {
                $end_date_pretty = date( 'F ', $end_date) . $end_date_pretty;
            }
        }

        // build date_range return string
        if( ! empty( $start_date ) ) {
            $date_range .= $start_date_pretty;
        }

        // check if there is an end date and append if not identical
        if( ! empty( $end_date ) ) {
            if( $end_date_pretty != $start_date_pretty ) {
                $date_range .= ' - ' . $end_date_pretty;
            }
        }
        return $date_range;
    }
    public static function show1000ExpQuestion($appTypeId){
        $appType = ApplicationType::findOne($appTypeId);
        $keywordList = ['certify', 'recertify'];
        if($appType && in_array($appType->keyword, $keywordList)){
            return true;
        }
        return false;
    }
    public static function getPdfToCustomFormMapping($pdfName){
        if($pdfName == 'iai-blank-practical-test-application-form.pdf'){
            return 'practical';
        }else if($pdfName == 'iai-blank-recert-with-1000-hours-application.pdf'){
            return 'recertify';
        }else if($pdfName == 'iai-blank-written-test-site-application-new-candidate.pdf'){
            return 'written';
        }
        return '';
    }
    public static function isDynamicFieldChecked($dynamicForm, $fieldName){
        if($dynamicForm != null){
            $fields = json_decode($dynamicForm->form_setup);

            foreach($fields as $field => $value){
                if($field == $fieldName) {
                    return $value == 'on';
                }
            }
        }
        return false;
    }
    public static function isDynamicFieldCheckedFindInForm($customForm, $fieldName){
        if($customForm != null){
            foreach($customForm as $key => $formDetails){
                foreach($formDetails as $key => $field){
                    $additionalFieldName = str_replace('W_EXAM_', 'W_EXAM_ADD_', $fieldName);
                    if($key == $fieldName || $key == $additionalFieldName)
                        return true;
                }
            }
        }
        return false;
    }

    public static function getDynamicFieldValue($dynamicForm, $fieldName, $defaultval){
        if($dynamicForm != null){
            $fields = json_decode($dynamicForm->form_setup);

            foreach($fields as $key => $field){
                if ($key == $fieldName) {
                    return $field;
                }
            }
        }
        return $defaultval;
    }
    public static function createPath($path) {
        if (is_dir($path)) return true;
        $prev_path = substr($path, 0, strrpos($path, '/', -2) + 1 );
        $return = self::createPath($prev_path);
        return ($return && is_writable($prev_path)) ? mkdir($path) : false;
    }

    private static function generateBasicCandidateSessionInfo($candidate, $writtenTestSession, $practicalTestSession)
    {
        $params = array();
        $params['P_FIRST_NAME'] = $params['W_FIRST_NAME'] = mb_strtoupper($candidate->first_name, 'UTF-8');
        $params['P_MIDDLE_NAME'] = $params['W_MIDDLE_NAME'] = mb_strtoupper($candidate->middle_name, 'UTF-8');
        $params['P_LAST_NAME'] = $params['W_LAST_NAME'] = mb_strtoupper($candidate->last_name, 'UTF-8');
        $params['P_CCO_CERT_NUMBER'] = $params['W_CCO_CERT_NUMBER'] = $candidate->ccoCertNumber;
        $params['P_SSN_6-9'] = $params['W_SSN_6-9'] = $candidate->ssn3;
        $params['P_SUFFIX'] = $params['W_SUFFIX'] = $candidate->suffix;
        $params['P_ADDRESS'] = $params['W_ADDRESS'] = $candidate->address;
        $params['P_DOB'] = $params['W_DOB'] = $candidate->birthday;
        $params['P_CITY'] = $params['W_CITY'] = $candidate->city;
        $params['P_STATE'] = $params['W_STATE'] = $candidate->state;
        $params['P_ZIP'] = $params['W_ZIP'] = $candidate->zip;
        $params['P_PHONE'] = $params['W_PHONE'] = $candidate->phone;
        $params['P_CELL'] = $params['W_CELL'] = $candidate->cellNumber;
        $params['P_FAX'] = $params['W_FAX'] = $candidate->faxNumber;
        $params['P_EMAIL'] = $params['W_EMAIL'] = $candidate->email;
        $params['P_COMPANY_NAME'] = $params['W_COMPANY_NAME'] = $candidate->company_name;
        $params['P_COMPANY_PHONE'] = $params['W_COMPANY_PHONE'] = $candidate->company_phone;
        $params['P_COMPANY_ADDRESS'] = $params['W_COMPANY_ADDRESS'] = $candidate->company_address;
        $params['P_COMPANY_CITY'] = $params['W_COMPANY_CITY'] = $candidate->company_city;
        $params['P_COMPANY_STATE'] = $params['W_COMPANY_STATE'] = $candidate->company_state;
        $params['P_COMPANY_ZIP'] = $params['W_COMPANY_ZIP'] = $candidate->company_zip;
        $params['W_REQUEST_ADA_ACCOMMODATIONS'] = $candidate->requestAda == 1 ? 'Yes' : 'Off';

        if ($writtenTestSession !== false) {
            $testSite = TestSite::findOne($writtenTestSession->test_site_id);
            if ($testSite != null) {
                $params['W_TC_ADDRESS'] = $testSite->address;
                $params['W_TC_CITY'] = $testSite->city;
                $params['W_TC_STATE'] = $testSite->state;
                $params['W_TC_ZIP'] = $testSite->zip;
                $params['W_TEST_SITE_NAME'] = $testSite->name;
            }
            $params['W_TEST_SITE_COORDINATOR_NAME'] = $writtenTestSession->getTestCoordinatorName(false);
            $params['W_TC_ADMIN_NUMBER'] = $writtenTestSession->session_number;
            $params['W_EXAM_DATE'] = '';
            if ($writtenTestSession->testing_date != null && $writtenTestSession->testing_date != '') {
                $params['W_EXAM_DATE'] = date('F d, Y', (strtotime($writtenTestSession->testing_date)));
            }
        }

        if ($practicalTestSession !== false) {
            $practicalTestSite = TestSite::findOne($practicalTestSession->test_site_id);
            if ($practicalTestSite != null) {
                $params['P_TC_ADDRESS'] = $practicalTestSite->address;
                $params['P_TC_CITY'] = $practicalTestSite->city;
                $params['P_TC_STATE'] = $practicalTestSite->state;
                $params['P_TC_ZIP'] = $practicalTestSite->zip;
                $params['P_TC_PHONE'] = $practicalTestSite->phone;
                $params['P_TC_FAX'] = $practicalTestSite->fax;
                $params['P_TC_EMAIL'] = $practicalTestSite->email;
            }
            $params['P_TEST_SITE_COORDINATOR_NAME'] = $practicalTestSession->getTestCoordinatorName(false);
            $params['P_SITE_NO'] = $practicalTestSession->session_number;
        }

        $newMergedParams = [];
        foreach ($params as $k => $val) {
            if (strpos($k, 'EMAIL') !== false) {
                $newMergedParams[$k] = ($val);
            } else {
                $newMergedParams[$k] = strtoupper($val);
            }
        }

        return $newMergedParams;
    }

    public static function deleteDirectory($dir) {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!self::deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }

        }

        return rmdir($dir);
    }

    public static function generateApplicationFormsNew($candidateId)
    {
        set_time_limit(1200);
        $candidate = Candidates::findOne($candidateId);
        $applicationType = $candidate->applicationType;

        if ($candidate) {
            $appTypeForms = ApplicationTypeFormSetup::findAll(['application_type_id' => $candidate->application_type_id]);
            $candidateSessions = $candidate->getAllTestSession();
            $writtenTestSession = false;
            $practicalTestSession = false;

            foreach($candidateSessions as $testSession){
                if ($testSession->getTestSessionTypeId() == TestSite::TYPE_WRITTEN) {
                    $writtenTestSession = TestSession::findOne($testSession->test_session_id);
                } elseif ($testSession->getTestSessionTypeId() == TestSite::TYPE_PRACTICAL) {
                    $practicalTestSession = TestSession::findOne($testSession->test_session_id);
                }
            }

            $fileFormDirectory = 'original';
            $zipFileName = 'app-forms.zip';
            $fullPdf = new Pdf();
            $candidateFolder = realpath(\Yii::$app->basePath) . '/web/app-forms/' . $candidate->getFolderDirectory() . '/' . $fileFormDirectory. '/';
            self::createPath($candidateFolder);
            $files_to_zip = array();
            $school = $candidate->getSchool();
            $confirmationPdf = realpath(\Yii::$app->basePath) . '/web/forms/confirmation/' . $school . '-confirmation-page.pdf';
            $files_to_zip[] = $confirmationPdf;
            $fullPdf->addFile($confirmationPdf);
            $params = self::generateBasicCandidateSessionInfo($candidate, $writtenTestSession, $practicalTestSession);
            $customForms = json_decode($candidate->custom_form_setup, true);
            $isWrittenRetake = false;

            if ($candidate->isRetake == 1 && $candidate->retakeType == TestSite::TYPE_WRITTEN) {
                $isWrittenRetake = true;
            }

            foreach ($appTypeForms as $appForm) {
                $formNamePdf = $appForm->form_name;

                if ($formNamePdf == AppFormHelper::WRITTEN_FORM_PDF && (!$applicationType->cross_out_cc_fields || $isWrittenRetake)) {
                    $formNamePdf = $formNamePdf.'-credit-card';
                } else if ($formNamePdf == AppFormHelper::RECERTIFY_FORM_PDF && (!$applicationType->cross_out_cc_fields || $isWrittenRetake)) {
                    $formNamePdf = $formNamePdf . '-credit-card';
                }

                $pdfFormPath = realpath(\Yii::$app->basePath) . '/web/forms/' . $formNamePdf . '.pdf';

                $targetCandidatePdfFile = $candidateFolder . $appForm->form_name.'.pdf';
                if (is_file($targetCandidatePdfFile)) {
                    unlink($targetCandidatePdfFile);
                }

                if($candidate->custom_form_setup != null && isset($customForms[$formNamePdf])){
                    $customFormData = $customForms[$formNamePdf];
                    $appForm->form_setup = json_encode($customFormData);
                }

                $dynaForms = json_decode($appForm->form_setup, true);

                if (is_file($pdfFormPath)) {
                    $pdf = new Pdf($pdfFormPath);

                    foreach($dynaForms as $key => $val){
                        if($val == 'on'){
                            $dynaForms[$key] = 'Yes';
                            if($key == 'W_EXAM_TLL_LINK-BELT'){
                                //workaround
                                $dynaForms['W_EXAM_CORE_LINK-BELT'] = 'Yes';
                            }
                        }
                    }

                    $mergedParams = array_merge($params, $dynaForms);
                    $pdf->fillForm($mergedParams)->saveAs($targetCandidatePdfFile);
                    $files_to_zip[] = $targetCandidatePdfFile;
                }
            }

            foreach (AppFormHelper::APP_FORM_PDFS as $appForm) {
                $pdfForm = $candidateFolder . $appForm . '.pdf';
                if (is_file($pdfForm)) {
                    $fullPdf->addFile($pdfForm);
                }
            }

            $cleanFirstName = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $candidate->first_name);
            $cleanLastName = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $candidate->last_name);
            $fullPdfFileName = $cleanLastName . ', ' . $cleanFirstName;
            $fullPdf->saveAs($candidateFolder . $fullPdfFileName . '.pdf');
            $result = self::create_zip($files_to_zip,$candidateFolder . '../' . $zipFileName, true);
            if ($result) {
                return true;
            }
        }
        return false;
    }

    public static function generateApplicationFormsZip($testSessionId)
    {
        $testSession = TestSession::findOne($testSessionId);

        if ($testSession) {
            $students = $testSession->getCandidates()->all();

            $zip = new \ZipArchive();
            $zipPath = realpath(\Yii::$app->basePath) . '/web/app-forms/test-session/' . md5($testSession->id);
            self::createPath($zipPath);

            if ($zip->open($zipPath . '/app-forms.zip', \ZipArchive::CREATE)!==TRUE) {
                exit("cannot open <$zipPath>\n");
            }

            $filesToZip = array();

            foreach ($students as $student) {
                $cleanFirstName = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $student->first_name);
                $cleanLastName = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $student->last_name);
                $fullPdfFileName = $cleanLastName . ', ' . $cleanFirstName;
                $fullPdfPath = realpath(\Yii::$app->basePath) . '/web/app-forms/' . $student->getFolderDirectory() . '/original/' . $fullPdfFileName . '.pdf';

                if (!is_file($fullPdfPath)) {
                    static::generateApplicationFormsNew($student->id);
                }

                $zip->addFile($fullPdfPath, $fullPdfFileName . '.pdf');
            }

            $zip->close();

            return $zipPath;
        }

        return false;
    }

    public static function generateApplicationForms($candidateId, $isGenerateNewPdf)
    {
        $candidate = Candidates::findOne($candidateId);

        if ($candidate) {

            $appType = ApplicationType::findOne($candidate->application_type_id);
            $appTypeForms = ApplicationTypeFormSetup::findAll(['application_type_id' => $candidate->application_type_id]);

            $writtenTestSession = false;
            $practicalTestSession = false;
            $candidateSessions = $candidate->getAllTestSession();
            foreach ($candidateSessions as $testSession) {
                if ($testSession->getTestSessionTypeId() == TestSite::TYPE_WRITTEN) {
                    $writtenTestSession = TestSession::findOne($testSession->test_session_id);
                } elseif ($testSession->getTestSessionTypeId() == TestSite::TYPE_PRACTICAL) {
                    $practicalTestSession = TestSession::findOne($testSession->test_session_id);
                }
            }

            $originalAppForms = realpath(\Yii::$app->basePath) . '/web/app-forms/' . $candidate->getFolderDirectory() . '/app-forms.zip';
            $hasOriginalForms = false;
            if (is_file($originalAppForms)) {
                $hasOriginalForms = true;
            }

            $fileFormDirectory = 'original';
            $zipFileName = 'app-forms.zip';
            if ($hasOriginalForms) {
                $fileFormDirectory = 'modified';
                $zipFileName = 'app-forms-latest.zip';
            }

            $candidateFolder = realpath(\Yii::$app->basePath) . '/web/app-forms/' . $candidate->getFolderDirectory().'/'.$fileFormDirectory.'/';
            $files_to_zip = array();
            self::createPath($candidateFolder);

            $school = $candidate->getSchool();
            $confirmationPdf = $candidateFolder.$school.'-confirmation-page.pdf';
            copy( realpath(\Yii::$app->basePath) . '/web/forms/confirmation/'.$school.'-confirmation-page.pdf', $confirmationPdf);
            $files_to_zip[] = $confirmationPdf;

            $params = self::generateBasicCandidateSessionInfo($candidate, $writtenTestSession, $practicalTestSession);

            $customForms = json_decode($candidate->custom_form_setup, true);

            $isWrittenRetake = false;

            if ($candidate->isRetake == 1 && $candidate->retakeType == TestSite::TYPE_WRITTEN) {
                $isWrittenRetake = true;
            }

            foreach ($appTypeForms as $appForm) {

                $formNamePdf = $appForm->form_name;

                if ($candidate->hasPreviousWrittenSession()  && $candidate->getWrittenTestSession() === false
                    && ($formNamePdf == AppFormHelper::WRITTEN_FORM_PDF || $formNamePdf == AppFormHelper::RECERTIFY_FORM_PDF)) {
                    continue;
                }

                if ($candidate->hasPreviousPracticalSession() && $candidate->getPracticalSession() === false &&
                    $formNamePdf == AppFormHelper::PRACTICAL_FORM_PDF) {
                    continue;
                }

                if ($isWrittenRetake && $formNamePdf == AppFormHelper::PRACTICAL_FORM_PDF) {
                    continue;
                }

                if ($formNamePdf == AppFormHelper::WRITTEN_FORM_PDF &&
                    (!$appType->cross_out_cc_fields || $candidate->hasPreviousSessions() || $isWrittenRetake)) {
                    $formNamePdf = $formNamePdf.'-credit-card';
                } elseif ($formNamePdf == AppFormHelper::RECERTIFY_FORM_PDF &&
                    (!$appType->cross_out_cc_fields || $candidate->hasPreviousSessions() || $isWrittenRetake) ) {
                    $formNamePdf = $formNamePdf.'-credit-card';
                }

                $pdfFormPath = realpath(\Yii::$app->basePath) . '/web/forms/'.$formNamePdf.'.pdf';

                $targetCandidatePdfFile = $candidateFolder.$appForm->form_name.'.pdf';
                if ($isGenerateNewPdf && is_file($targetCandidatePdfFile)) {
                    unlink($targetCandidatePdfFile);
                }

                if ($isGenerateNewPdf && is_file($pdfFormPath)) {
                    $pdf = new Pdf($pdfFormPath);

                    $formName = $appForm->form_name;
                    if ($candidate->custom_form_setup != null && isset($customForms[$formName])) {
                        $customFormData = $customForms[$formName];
                        $appForm->form_setup = json_encode($customFormData);
                    }

                    $dynaForms = json_decode($appForm->form_setup, true);

                    foreach ($dynaForms as $key => $val) {
                        if ($val == 'on') {
                            $dynaForms[$key] = 'Yes';
                            if ($key == 'W_EXAM_TLL_LINK-BELT') {
                                $dynaForms['W_EXAM_CORE_LINK-BELT'] = 'Yes';
                            }
                        }
                    }

                    $mergedParams = array_merge($params, $dynaForms);

                    $pdf->fillForm($mergedParams)->saveAs($targetCandidatePdfFile);
                    $files_to_zip[] = $targetCandidatePdfFile;
                } else {
                    $files_to_zip[] = $targetCandidatePdfFile;
                }
            }

            $result = self::create_zip($files_to_zip,$candidateFolder . '../' . $zipFileName, true);
            if ($result) {
                return true;
            }
        }
        return false;
    }

    public static function getOriginalAppForms($candidateSesssion){
        return self::getOriginalAppFormsByCandidateId($candidateSesssion->candidate_id);
    }
    public static function getOriginalAppFormsByCandidateId($candidateId){
        $candidate = Candidates::findOne($candidateId);
        //we get the latest
        $candidateFolder = realpath(\Yii::$app->basePath) . '/web/app-forms/'.$candidate->getFolderDirectory().'/original/';
        $appFormFile = $candidateFolder.'../app-forms-latest.zip';
        if(is_file($appFormFile))
            return $appFormFile;

        $candidateFolder = realpath(\Yii::$app->basePath) . '/web/app-forms/'.$candidate->getFolderDirectory().'/original/';
        $appFormFile = $candidateFolder.'../app-forms.zip';
        if(is_file($appFormFile))
            return $appFormFile;
        return false;
    }
    /* creates a compressed zip file */
    public static function create_zip($files = array(),$destination = '',$overwrite = false) {
        //if the zip file already exists and overwrite is false, return false
        if(file_exists($destination) && !$overwrite) { return false; }
        //vars
        $valid_files = array();
        //if files were passed in...
        if(is_array($files)) {
            //cycle through each file
            foreach($files as $file) {
                //make sure the file exists
                if(file_exists($file)) {
                    $valid_files[] = $file;
                }
            }
        }
        //if we have good files...
        if(count($valid_files)) {
            //create the archive
            $zip = new \ZipArchive();

            if($overwrite && file_exists($destination) === false){
                $overwrite = false;
            }

            $res = $zip->open($destination,$overwrite ? \ZipArchive::OVERWRITE : \ZipArchive::CREATE);
            if($res !== true) {
                var_dump($res);
                //die;
                return false;
            }
            //add the files
            foreach($valid_files as $file) {
                $zip->addFile($file,basename($file));
            }
            //debug
            //echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;

            //close the zip -- done!
            $zip->close();

            //check to make sure the file exists
            return file_exists($destination);
        }
        else
        {
            return false;
        }
    }

    public static function buildActionWrapper($path, $linkID, $showDelete = true, $extraLinks = null, $extraHtmlLinks = false, $showView = true){

        $extra = '';

        if(is_array($extraLinks)){
            foreach($extraLinks as $link ){
                $elink = '<i class="fa '.$link['ico'].'" style="width:15px"></i><span style="font-size: 14px;"> ' . $link['label'] . '</span>';

                $extraParams = ['class' => ''];
                if(isset($link['target'])){
                    $extraParams['target'] = $link['target'];
                }


                $ehtml = Html::a($elink, $link['url'], $extraParams);
                $extra .= '<li>' . $ehtml .'</li>';
            }
        }
        if($extraHtmlLinks !== false){
            $extra .= $extraHtmlLinks;
        }
        $linkView = '';
        if($showView){
            $linkViewHTML =     '<i class="fa fa-eye" style="width:15px"></i><span style="font-size: 14px;"> View</span>';
            $linkView = Html::a($linkViewHTML, [$path.'/view', 'id'=>$linkID], ['class'=>'']);
        }
        $linkEdit = '';
        if(UtilityHelper::isSuperAdmin()){
            $linkEditHTML =     '<i class="fa fa-pencil" style="width:15px"></i><span style="font-size: 14px;"> Edit</span>';
            $linkEdit = Html::a($linkEditHTML, [$path.'/update', 'id'=>$linkID], ['class'=>'']);
        }
        $linkDelHTML =     '<i class="fa fa-trash" style="width:15px"></i><span style="font-size: 14px;"> Delete</span>';
        $linkDel = Html::a($linkDelHTML, [$path.'/delete', 'id'=>$linkID], ['class'=>'link-delete',/*'data-confirm' => "Are you sure you want to delete?", 'data-method'=>'post'*/]);

        $rest = '<a href="#" class="show-action"><i class="fa fa-cogs"></i> Actions</a>
                    <div class="pop-content" style="display: none">
                        <ul style="list-style-type: none; margin: 0; padding: 0;">
                            <li>' . $linkView . '</li>
                            <li>' . $linkEdit . '</li>
                            '. $extra;

        if($showDelete){
            $rest .=      '<li>' . $linkDel . '</li>';
        }

        $rest .=    '</ul></div>';
        return $rest;
    }

    private static function getBrandingMapping($branding){
        $branding = strtolower($branding);
        return $branding;
    }
    public static function getShowBrandingLogo($site){
        $filePath = realpath(\Yii::$app->basePath) . '/web';
        $brandingPath = '/images/site/'.$site.'/logo.png';
        $logoPath = $filePath .$brandingPath;
        if(is_file($logoPath))
            return $brandingPath;
        return '';
    }
    public static function getSiteBrandingLogo(){
        //session_start();
        $branding = isset($_SESSION['branding']) ? $_SESSION['branding'] : '';
        $filePath = realpath(\Yii::$app->basePath) . '/web';
        $brandingPath = '/images/site/'.self::getBrandingMapping($branding).'/logo.png';
        $logoPath = $filePath .$brandingPath;
        if(is_file($logoPath))
            return $brandingPath;
        return '/images/site/default/logo.png';
    }
    public static function getCurrentBranding(){
        $branding = isset($_SESSION['branding']) ? $_SESSION['branding'] : '';
        return $branding;
    }
    public static function getSiteBrandingInfo(){
        //session_start();
        $info = array();
        $logo = '';
        $logoMedal = '';
        $branding = isset($_SESSION['branding']) ? $_SESSION['branding'] : '';
        $filePath = realpath(\Yii::$app->basePath) . '/web';
//    	$brandingPath = '/images/site/'.self::getBrandingMapping($branding).'/logo.png';
        $brandingPath = '/images/site/'.self::getBrandingMapping($branding).'/logo-sm.png';
        $logoPath = $filePath .$brandingPath;
        if(is_file($logoPath)){
            $logo = $brandingPath;
            $logoMedal = $brandingPath;
        }
        else{
            $logo = '/images/site/default/logo.png';
            $logoMedal = '';
        }
        $brandingPath = '/images/site/'.self::getBrandingMapping($branding).'/goldmedal.png';
        $logoMedalPath = $filePath .$brandingPath;
        if(is_file($logoMedalPath)){
            $logoMedal = $brandingPath;
        }
        else{
            $logoMedal = '';
        }

        $info['logo'] = $logo;
        $info['logo-medal'] = $logoMedal;
        //we parse the info file
        $targetFeedFile = $filePath. '/images/site/'.self::getBrandingMapping($branding).'/info.txt';
        if(!is_file($targetFeedFile)){
            $targetFeedFile = $filePath. '/images/site/default/info.txt';
        }
        if(is_file($targetFeedFile)){
            $handle = fopen($targetFeedFile, "r");

            while (($row = fgetcsv($handle, 0, "|")) !== FALSE) {
                $info[$row[0]] = $row[1];
            }
        }else{
            $info['phone'] = 'N/A';
            $info['displayPhone'] = 'N/A';
            $info['address'] = 'N/A';
            $info['fullBrandingName'] = '';
            $info['logo-medal'] = '';
        }
        return $info;
    }

    static public function format_phone_us($phone) {
        // note: making sure we have something
        if(!isset($phone{3})) { return ''; }
        // note: strip out everything but numbers
        $phone = preg_replace("/[^0-9]/", "", $phone);
        $length = strlen($phone);
        switch($length) {
            case 7:
                return preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);
                break;
            case 10:
                return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $phone);
                break;
            case 11:
                return preg_replace("/([0-9]{1})([0-9]{3})([0-9]{3})([0-9]{4})/", "$1($2) $3-$4", $phone);
                break;
            default:
                return $phone;
                break;
        }
    }

    public static function getAvailableReports(){
        $reports = array();
        $reports['iai'] = 'NCCCO Accounts Payable Report';
        $reports['unsigned'] = 'Unsigned Application Report';
        $reports['promo'] = 'Promo Report';
        $reports['readiness'] = 'Class Readiness Packet Report';
        $reports['pass_fail'] = 'Candidate Pass / Fail Report';
        $reports['submit_app_iai'] = 'Submit Application to NCCCO Testing Services Report';
        $reports['pending_payments'] = 'Pending Payments Report';
        $reports['bookkeeping'] = 'Bookkeeping Report';
        $reports['discrepancy'] = 'Discrepancy Report';
        return $reports;
    }

    public static function geReportFullDescription($reportType) {
        $reports = self::getAvailableReports();
        return isset($reports[$reportType]) ? $reports[$reportType] :'Report Details';
    }



    public static function getAppConfig($code, $defaultVal){
        $appConfig = AppConfig::findOne(['code' => $code]);
        if($appConfig != null){
            return $appConfig->val;
        }
        return $defaultVal;
    }

    public static function getPromoCodeReports($postParams){

        $promoCode = $postParams['promoCode'];
        $startDate = $postParams['start_date'];
        $endDate = $postParams['end_date'];
        $sql = "select c.id, c.first_name, c.last_name, ct.amount, ct.transactionId, c.referralCode,  ct.date_created
from candidates c, candidate_transactions ct where c.id = ct.candidateId
and paymentType = 3 and ct.date_created > '".$startDate."' and ct.date_created < '".$endDate."'";
        if($promoCode != ''){
            $sql .= " and ct.transactionId = '".$promoCode."' ";
        }
        $sql .= " order by ct.transactionId asc, ct.date_created asc";
        $command = \Yii::$app->db->createCommand($sql);
        $promos  = $command->queryAll();
        return $promos;
    }


    /**
     * build the markup for the sorter "popover"
     *
     * @param $label        string  Text to display in the th link
     * @param $sortParam    string  param against which the sorting is applied. matches a model prop
     * @param $qStrNoSort   string  current query string without the sort param e.g. keep all filters
     * @param $currentSort  string  param against the table is currently sorted, including asc / desc (since desc == -asc)
     * @param $iconType     string  vlaue for the fontawesome icon  values: alpha / numeric, default = alpha
     * @return string       string  html to be concatenated with the output
     *
     */
    public static function tableSortHeader($label, $sortParam, $iconType = 'alpha'){

        $searchPath= \Yii::$app->request->getBaseUrl();

        /* QueryString Values that will be used to populate custom search filters*/
        $qStr = \Yii::$app->request->getQueryParams();
        /* Build QueryString without the sort Param */
        $qStrNoSort = $qStr;
        unset($qStrNoSort['sort']);
        $currentSort = isset($qStr['sort']) ? $qStr['sort'] : '' ;
        $qStrNoSort = http_build_query($qStrNoSort, '', '&');

        $rest = '<a href="#" class="sorter-link">'. $label.' <i class="fa fa-filter"></i></a>';
        $rest .= '<div class="sorter-content"><div class="sorter-wrapper clearfix">';

        if ( $currentSort == $sortParam ){
            $rest .='<span  title="Sorted by '. $label .' (Ascending)"><i class="fa fa-sort-'.$iconType.'-asc"></i></span>';
        }else{
            $rest .='<a href="'.$searchPath.'?'.$qStrNoSort.'&sort='.$sortParam.'" title="Sort by '.$label.' (Ascending)"><i class="fa fa-sort-'.$iconType.'-asc"></i></a>';
        }

        if ( $currentSort == '-'.$sortParam){
            $rest .='<span  title="Sorted by '.$label.' (Descending)"><i class="fa fa-sort-'.$iconType.'-desc"></i></span>';
        }else{
            $rest .='<a href="'.$searchPath.'?'.$qStrNoSort.'&sort=-'.$sortParam.'" title="Sort by '.$label.' (Descending)"><i class="fa fa-sort-'.$iconType.'-desc"></i></a>';
        }

        return $rest.'</div></div>';
    }

    public static function downloadAppForm($appFormPath, $candidate){
        $zip = new \ZipArchive();
        $res = $zip->open($appFormPath);
        $realCandidateBaseFolder = realpath(\Yii::$app->basePath) . '/web/app-forms/'.$candidate->getFolderDirectory();
        $candidateFolder = $realCandidateBaseFolder.'/unzip/'.date('Ymd.His');
        UtilityHelper::createPath($candidateFolder);

        if ($res === TRUE) {
            $zip->extractTo($candidateFolder);
            $zip->close();

        } else {

        }
        $dynamicForms = AppFormHelper::getDynamicFormInfo();
        $fileOrders = [];
        $school = $candidate->getSchool();
        $fileOrders[] = $school.'-confirmation-page.pdf';
        foreach($dynamicForms as $key => $val){
            $fileOrders[] = $key.'.pdf';
        }

        $pdf = new Pdf();
        foreach($fileOrders as $fileNames){
            $pdfName = $candidateFolder.'/'.$fileNames;
            if(is_file($pdfName)){

                $pdf->addFile($pdfName);
            }
        }
        $mergedFile = $realCandidateBaseFolder.'/latest-app-form1.pdf';
        if(is_file($mergedFile)){
            unlink($mergedFile);
        }
        $pdf->saveAs($mergedFile);
        $mergedFile1 = $realCandidateBaseFolder.'/latest-app-form.pdf';
        if(is_file($mergedFile1)){
            unlink($mergedFile1);
        }

        exec('pdftk '.$mergedFile.' output '.$mergedFile1.' flatten');
        return $mergedFile1;
    }

    public static function getReadinessWritten(){
        $written = [];
        $written[] = ['name' => 'Core', 'formName' => 'W_EXAM_CORE'];
        $written[] = ['name' => 'LBC', 'formName' => 'W_EXAM_LBC'];
        $written[] = ['name' => 'LBT', 'formName' => 'W_EXAM_LBT'];
        $written[] = ['name' => 'SW', 'formName' => 'W_EXAM_TLL'];
        $written[] = ['name' => 'FX', 'formName' => 'W_EXAM_TSS'];

        return $written;
    }
    public static function getReadinessPractical(){
        $practical = [];
        $practical[] = ['name' => 'SW Cab', 'formName' => 'P_TELESCOPIC_TLL'];
        $practical[] = ['name' => 'FX Cab', 'formName' => 'P_TELESCOPIC_TSS'];

        return $practical;
    }
    public static function generateSessionCertificates($testSessionId, $regenerateAll = false, $reportParams = [], $excludeRetake = false)
    {
        $testSession = TestSession::findOne($testSessionId);
        $testSessionCounterpart = null;
        if (isset($testSession->practical_test_session_id)) {
            $testSessionCounterpart = TestSession::findOne($testSession->practical_test_session_id);
        } else {
            $testSessionCounterpart = TestSession::findOne(['practical_test_session_id' => $testSession->id]);
        }

        $candidateSessions = CandidateSession::findAll(['test_session_id' => [$testSession->id, $testSessionCounterpart->id]]);
        $candidateIds = array_map(function($candidateSession) {
            return $candidateSession->candidate_id;
        }, $candidateSessions);
        $candidates = Candidates::find()->where(['id' => $candidateIds, 'isArchived' => 0])->orderBy(['last_name' => SORT_ASC])->all();

        $testSessionFolder = realpath(\Yii::$app->basePath) . '/web/app-forms/test-session/' . $testSession->getFolderDirectory().'/';
        self::createPath($testSessionFolder);
        $mergedFile = $testSessionFolder . 'session-student-certificates.pdf';

        if (is_file($mergedFile)) {
            unlink($mergedFile);
        }

        $candidateNames = [];

        foreach ($candidates as $candidate) {
            $noWritten = !$candidate->hasWrittenTest;

            if ($candidate->applicationType->name == 'Test' || $noWritten) {
                continue;
            }

            self::generateCertificate($candidate->id, $testSession, $reportParams);
        }

        return self::mergeAllSessionStudentCerts($testSessionId);
    }

    public static function mergeAllSessionStudentCerts($testSessionId)
    {
        $testSession = TestSession::findOne($testSessionId);
        $testSessionCounterpart = null;
        if (isset($testSession->practical_test_session_id)) {
            $testSessionCounterpart = TestSession::findOne($testSession->practical_test_session_id);
        } else {
            $testSessionCounterpart = TestSession::findOne(['practical_test_session_id' => $testSession->id]);
        }

        $candidateSessions = CandidateSession::findAll(['test_session_id' => [$testSession->id, $testSessionCounterpart->id]]);
        $candidateIds = array_map(function($candidateSession) {
            return $candidateSession->candidate_id;
        }, $candidateSessions);
        $candidates = Candidates::find()->where(['id' => $candidateIds, 'isArchived' => 0])->orderBy(['last_name' => SORT_ASC])->all();

        $testSessionFolder = realpath(\Yii::$app->basePath) . '/web/app-forms/test-session/'.$testSession->getFolderDirectory().'/';

        $files_to_zip = array();
        self::createPath($testSessionFolder);
        $mergedFile = $testSessionFolder.'session-student-certificates.pdf';

        if (is_file($mergedFile)) {
            unlink($mergedFile);
        }

        $candidatesPdfs = array();
        $pdf = new Pdf();

        foreach ($candidates as $candidate) {
            $noWritten = !$candidate->hasWrittenTest;

            if ($candidate->applicationType->name === 'Test' || $noWritten) {
                continue;
            }

            $candidateFolder = realpath(\Yii::$app->basePath) . '/web/app-forms/'.$candidate->getFolderDirectory().'/attachments/';
            $confirmationPdf = $candidateFolder.'certificates.pdf';
            if (is_file($confirmationPdf)) {
                $candidatesPdfs[] = $confirmationPdf;
            }
        }

        foreach ($candidatesPdfs as $candidateCertPath) {
            $pdf->addFile($candidateCertPath);
        }

        if (count($candidatesPdfs) > 0) {
            $saved = $pdf->flatten()->saveAs($mergedFile);
            if ($saved) {
                return $mergedFile;
            }
        }

        return false;
    }

    public static function generateCertificate($candidateId, $testSessionReport = false, $reportParams = [])
    {
        $candidate = Candidates::findOne($candidateId);

        if ($candidate) {
            $appType = ApplicationType::findOne($candidate->application_type_id);
            $appTypeForms = ApplicationTypeFormSetup::findAll(['application_type_id' => $candidate->application_type_id]);

            $writtenTestSession = false;
            $practicalTestSession = false;
            $candidateSessions = $candidate->getAllTestSession();
            foreach ($candidateSessions as $testSession) {
                if ($testSession->getTestSessionTypeId() == TestSite::TYPE_WRITTEN) {
                    $writtenTestSession = TestSession::findOne($testSession->test_session_id);
                } elseif ($testSession->getTestSessionTypeId() == TestSite::TYPE_PRACTICAL) {
                    $practicalTestSession = TestSession::findOne($testSession->test_session_id);
                }
            }
            $schoolType = '';
            if ($writtenTestSession !== false) {
                $schoolType = strtolower($writtenTestSession->school);
            } elseif ($practicalTestSession !== false) {
                $schoolType = strtolower($practicalTestSession->school);
            }

            $candidateFolder = realpath(\Yii::$app->basePath) . '/web/app-forms/' . $candidate->getFolderDirectory() . '/attachments/';
            $files_to_zip = array();
            self::createPath($candidateFolder);
            $confirmationPdf = $candidateFolder . 'certificates.pdf';
            $pdfFormPath = realpath(\Yii::$app->basePath) . '/web/forms/confirmation/' . $schoolType . '-certificate-auto.pdf';

            if (is_file($confirmationPdf)) {
                unlink($confirmationPdf);
            }

            if (is_file($pdfFormPath)) {
                $pdf = new Pdf($pdfFormPath);
                $session = false;
                if ($testSessionReport !== false) {
                    $session = $testSessionReport;
                } elseif ($writtenTestSession !== false) {
                    $session = $writtenTestSession;
                } elseif ($practicalTestSession !== false) {
                    $session = $practicalTestSession;
                }

                $params = [];
                $params['STUDENT'] = $candidate->getFullName();
                $params['INSTRUCTOR'] = isset($reportParams['instructorName']) ? $reportParams['instructorName'] : '';
                $params['INSTRUCTOR_SUB'] = isset($reportParams['instructorName']) ? $reportParams['instructorName'] . ', Instructor': '';
                $params['DATE'] = isset($reportParams['certDate']) ? $reportParams['certDate'] : '';
                if ( $params['INSTRUCTOR'] != '') {
                    self::checkInstructorNameLog( $params['INSTRUCTOR']);
                }

                $newMergedParams = [];
                foreach ($params as $k => $val) {
                    if (strpos($k, 'mail') !== false) {
                        $newMergedParams[$k] = ($val);
                    } elseif ('STUDENT' == $k) {
                        $newMergedParams[$k] = mb_strtoupper($val, 'UTF-8');
                    } else {
                        $newMergedParams[$k] = ucwords($val);
                    }
                }

                $saved = $pdf->fillForm($newMergedParams)->needAppearances()->saveAs($confirmationPdf);
                if ($saved) {
                    return $confirmationPdf;
                }
            }
        }
        return false;
    }

    public static function getLast5Instructor(){
        $sql = "select instructor from last_instructor order by id desc limit 5";
        $command = \Yii::$app->db->createCommand($sql);
        $instructor = $command->queryAll();
        $instructorNames = [];
        foreach($instructor as $key => $val){
            $instructorNames[] = $val['instructor'];
        }
        return  $instructorNames;
    }
    private static function checkInstructorNameLog($instructorName){
        $instructorNames = self::getLast5Instructor();
        $isFound = false;
        foreach($instructorNames as $name){
            if($name == $instructorName){
                $isFound = true;
            }
        }
        if(!$isFound){
            //we insert it
            $lastInstructor = new LastInstructor();
            $lastInstructor->instructor= $instructorName;
            $lastInstructor->save();
        }
    }

    public static function addCandidateInitialApplicationCharge($candidate){
        $appType = ApplicationType::findOne($candidate->application_type_id);

        $initialStudentCharge = CandidateTransactions::findOne(['paymentType' => CandidateTransactions::TYPE_STUDENT_CHARGE, 'candidateId' => $candidate->id]);

        if($initialStudentCharge == null){
            $candidateTransaction = new CandidateTransactions();
            $candidateTransaction->transactionId = '';
            $candidateTransaction->amount = $appType->price;
            $candidateTransaction->remarks = 'Application Charge - ' . $appType->description;
            $candidateTransaction->paymentType = CandidateTransactions::TYPE_STUDENT_CHARGE;
            $candidateTransaction->candidateId = $candidate->id;
            $candidateTransaction->save();
        }
    }

    public static function isSuperAdmin() {
        if (User::ROLE_ADMIN == \Yii::$app->session->get('role')) {
            return true;
        }

        $hasSessionRoles = is_array(\Yii::$app->session->get('roles'));

        if ($hasSessionRoles && in_array(UserRole::SUPER_ADMIN, \Yii::$app->session->get('roles'))) {
            return true;
        }

        return false;
    }

    protected function isWindows()
    {
        if (PHP_OS == 'WINNT' || PHP_OS == 'WIN32') {
            return true;
        } else {
            return false;
        }
    }
    static public function runCommand($command, $params){

        if (self::isWindows() === true) {
            pclose(popen('start /b ' . \Yii::$app->basePath.'\yii.bat '.$command.' '.$params, 'r'));
        } else {
            //pclose(popen(\Yii::$app->basePath.'/yii '. $command.' '.$params . ' /dev/null &', 'r'));
            shell_exec(\Yii::$app->basePath.'/yii '. $command.' '.$params . ' > /dev/null 2>/dev/null &');
        }
        return true;
    }
    public static function getOperatingTime(){
        $start=strtotime('00:00');
        $end=strtotime('24:00');
        $timeSlot = [];

        for ($i=$start;$i<=$end;$i = $i + 15*60)
        {

            //write your if conditions and implement your logic here
            $timeInfo = '';
            $timeDisplay = '';
            if($i == $end){
                $timeInfo =  '12:00 am';
                $timeDisplay = '12:00 am';
            }else{
                $timeInfo = date('g:i a',$i);
                $timeDisplay = date('g:i a',$i);

            }
            $timeSlot[$timeInfo] = $timeDisplay;

        }
        return $timeSlot;
    }
}
