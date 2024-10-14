<?php

namespace app\helpers;

use app\models\ApplicationTypeFormSetup;
class AppFormHelper {
    const PRACTICAL_FORM_PDF = 'iai-blank-practical-test-application-form';
    const WRITTEN_FORM_PDF = 'iai-blank-written-test-site-application-new-candidate';
    const RECERTIFY_FORM_PDF = 'iai-blank-recert-with-1000-hours-application';
    const APP_FORM_PDFS = [self::WRITTEN_FORM_PDF, self::RECERTIFY_FORM_PDF, self::PRACTICAL_FORM_PDF];

    public static function hasRecertifyPdf($appTypeId){
        $appForms = ApplicationTypeFormSetup::findAll(['application_type_id' => $appTypeId]);

        foreach($appForms as $form){
            if($form->form_name == self::RECERTIFY_FORM_PDF){
                return true;
            }
        }
        return false;
    }

    public static function getDynamicFormInfo()
    {
        $dynamicForm = array();

        $dynamicForm[self::WRITTEN_FORM_PDF] = array();
        $dynamicForm[self::WRITTEN_FORM_PDF]['name'] = 'Written';
        $dynamicForm[self::WRITTEN_FORM_PDF]['iai'] = array();
        $dynamicForm[self::WRITTEN_FORM_PDF]['iai']['W_FEE_LATE'] = ['name' => 'Candidate Late Fee (if applicable)', 'amount' =>50];
        $dynamicForm[self::WRITTEN_FORM_PDF]['iai']['W_FEE_INCOMPLETE'] = ['name' => 'Incomplete Application Fee (if applicable)', 'amount' =>30];
        $dynamicForm[self::WRITTEN_FORM_PDF]['iai']['W_FEE_UPDATE_REPLACE'] = ['name' => 'Updated/Replacement Card', 'amount' =>25];
        $dynamicForm[self::WRITTEN_FORM_PDF]['iai']['W_FEE_CORE_1'] = ['name' => 'Core Exam plus one Specialty Exam', 'amount' =>165];
        $dynamicForm[self::WRITTEN_FORM_PDF]['iai']['W_FEE_CORE_2'] = ['name' => 'Core Exam plus two Specialty Exams', 'amount' =>175];
        $dynamicForm[self::WRITTEN_FORM_PDF]['iai']['W_FEE_CORE_3'] = ['name' => 'Core Exam plus three Specialty Exams', 'amount' =>185];
        $dynamicForm[self::WRITTEN_FORM_PDF]['iai']['W_FEE_CORE_4'] = ['name' => 'Core Exam plus four Specialty Exams', 'amount' =>195];
        $dynamicForm[self::WRITTEN_FORM_PDF]['iai']['W_FEE_ADDED_CORE'] = ['name' => 'Core Exam only or Core plus one Specialty (Retest)', 'amount' =>165];
        $dynamicForm[self::WRITTEN_FORM_PDF]['iai']['W_FEE_ADDED_SPECIALTY_1'] = ['name' => 'One Specialty Exam (Retest or Added Specialty)', 'amount' =>65];
        $dynamicForm[self::WRITTEN_FORM_PDF]['iai']['W_FEE_ADDED_SPECIALTY_2'] = ['name' => 'Two Specialty Exams (Retest or Added Specialty)', 'amount' =>75];
        $dynamicForm[self::WRITTEN_FORM_PDF]['iai']['W_FEE_ADDED_SPECIALTY_3'] = ['name' => 'Three Specialty Exams (Retest or Added Specialty)', 'amount' =>85];
        $dynamicForm[self::WRITTEN_FORM_PDF]['iai']['W_FEE_ADDED_SPECIALTY_4'] = ['name' => 'Four Specialty Exams (Retest)', 'amount' =>95];

        $dynamicForm[self::WRITTEN_FORM_PDF]['cranes'] = array();
        $dynamicForm[self::WRITTEN_FORM_PDF]['cranes']['W_EXAM_CORE'] = ['name' => 'Mobile Core Exam', 'number' => '652603'];
        $dynamicForm[self::WRITTEN_FORM_PDF]['cranes']['W_EXAM_LBC'] = ['name' => 'Lattice Boom Crawler (LBC)', 'number' => '652620,652607'];
        $dynamicForm[self::WRITTEN_FORM_PDF]['cranes']['W_EXAM_LBT'] = ['name' => 'Lattice Boom Truck (LBT) ', 'number' => '652609,652610'];
        $dynamicForm[self::WRITTEN_FORM_PDF]['cranes']['W_EXAM_TLL'] = ['name' => 'Telescopic Boom-Swing Cab (TLL)', 'number' => '652612,652613'];
        $dynamicForm[self::WRITTEN_FORM_PDF]['cranes']['W_EXAM_TSS'] = ['name' => 'Telescopic Boom-Fixed Cab (TSS)', 'number' => '652616,652660'];
        $dynamicForm[self::WRITTEN_FORM_PDF]['cranes']['W_EXAM_BTF'] = ['name' => 'Boom Truck-Fixed Cab (BTF)', 'number' => '652671'];
        $dynamicForm[self::WRITTEN_FORM_PDF]['cranes']['W_EXAM_TOWER'] = ['name' => 'Tower Crane', 'number' => '654601'];
        $dynamicForm[self::WRITTEN_FORM_PDF]['cranes']['W_EXAM_OVERHEAD'] = ['name' => 'Overhead Crane', 'number' => '653601'];

        $dynamicForm[self::WRITTEN_FORM_PDF]['practical-cranes']['P_LATTICE'] = ['name' => 'Lattice Boom Crane'];
        $dynamicForm[self::WRITTEN_FORM_PDF]['practical-cranes']['P_TELESCOPIC_TLL'] = ['name' => 'Telescopic Boom Crane - Swing Cab (TLL)'];
        $dynamicForm[self::WRITTEN_FORM_PDF]['practical-cranes']['P_TELESCOPIC_TSS'] = ['name' => 'Telescopic Boom Crane - Fixed Cab (TSS)'];
        $dynamicForm[self::WRITTEN_FORM_PDF]['practical-cranes']['P_TOWER'] = ['name' => 'Tower Crane'];
        $dynamicForm[self::WRITTEN_FORM_PDF]['practical-cranes']['P_OVERHEAD'] = ['name' => 'Overhead Crane'];

        $dynamicForm[self::PRACTICAL_FORM_PDF] = array();
        $dynamicForm[self::PRACTICAL_FORM_PDF]['name'] = 'Practical';
        $dynamicForm[self::PRACTICAL_FORM_PDF]['practical-cranes']['P_LATTICE'] = ['name' => 'Lattice Boom Crane'];
        $dynamicForm[self::PRACTICAL_FORM_PDF]['practical-cranes']['P_TELESCOPIC_TLL'] = ['name' => 'Telescopic Boom Crane - Swing Cab (TLL)'];
        $dynamicForm[self::PRACTICAL_FORM_PDF]['practical-cranes']['P_TELESCOPIC_TSS'] = ['name' => 'Telescopic Boom Crane - Fixed Cab (TSS)'];
        $dynamicForm[self::PRACTICAL_FORM_PDF]['practical-cranes']['P_TOWER'] = ['name' => 'Tower Crane'];
        $dynamicForm[self::PRACTICAL_FORM_PDF]['practical-cranes']['P_OVERHEAD'] = ['name' => 'Overhead Crane'];

        $dynamicForm[self::RECERTIFY_FORM_PDF] = array();
        $dynamicForm[self::RECERTIFY_FORM_PDF]['name'] = 'Recertify';
        $dynamicForm[self::RECERTIFY_FORM_PDF]['iai'] = array();
        $dynamicForm[self::RECERTIFY_FORM_PDF]['iai']['W_FEE_CORE_1'] = ['name' => 'Mobile Core Exam plus one Specialty Exam', 'amount' =>150];
        $dynamicForm[self::RECERTIFY_FORM_PDF]['iai']['W_FEE_CORE_2'] = ['name' => 'Mobile Core Exam plus two Specialty Exams', 'amount' =>155];
        $dynamicForm[self::RECERTIFY_FORM_PDF]['iai']['W_FEE_CORE_3'] = ['name' => 'Mobile Core Exam plus three Specialty Exams', 'amount' =>160];
        $dynamicForm[self::RECERTIFY_FORM_PDF]['iai']['W_FEE_CORE_4'] = ['name' => 'Mobile Core Exam plus four Specialty Exams', 'amount' =>165];

        $dynamicForm[self::RECERTIFY_FORM_PDF]['iai']['W_FEE_RETEST_CORE_1'] = ['name' => 'Mobile Core Exam or Core plus one Specialty Exam (Retest)', 'amount' =>150];
        $dynamicForm[self::RECERTIFY_FORM_PDF]['iai']['W_FEE_RETEST_SPECIALTY_1'] = ['name' => 'One Mobile Specialty Exam (Retest)', 'amount' =>50];
        $dynamicForm[self::RECERTIFY_FORM_PDF]['iai']['W_FEE_RETEST_SPECIALTY_2'] = ['name' => 'Two Mobile Specialty Exams (Retest)', 'amount' =>55];
        $dynamicForm[self::RECERTIFY_FORM_PDF]['iai']['W_FEE_RETEST_SPECIALTY_3'] = ['name' => 'Three Mobile Specialty Exams (Retest)', 'amount' =>60];
        $dynamicForm[self::RECERTIFY_FORM_PDF]['iai']['W_FEE_RETEST_SPECIALTY_4'] = ['name' => 'Four Mobile Specialty Exams (Retest)', 'amount' =>65];
        $dynamicForm[self::RECERTIFY_FORM_PDF]['iai']['W_FEE_ADDED_SPECIALTY_1'] = ['name' => 'One Mobile Specialty Exam', 'amount' =>65];
        $dynamicForm[self::RECERTIFY_FORM_PDF]['iai']['W_FEE_ADDED_SPECIALTY_2'] = ['name' => 'Two Mobile Specialty Exam', 'amount' =>75];
        $dynamicForm[self::RECERTIFY_FORM_PDF]['iai']['W_FEE_ADDED_SPECIALTY_3'] = ['name' => 'Three Mobile Specialty Exam', 'amount' =>85];
        $dynamicForm[self::RECERTIFY_FORM_PDF]['iai']['W_FEE_ADDED_TOWER'] = ['name' => 'Tower Crane Exam', 'amount' =>50];
        $dynamicForm[self::RECERTIFY_FORM_PDF]['iai']['W_FEE_ADDED_OVERHEAD'] = ['name' => 'Overhead Crane Exam', 'amount' =>50];
        $dynamicForm[self::RECERTIFY_FORM_PDF]['iai']['W_FEE_LATE'] = ['name' => 'Candidate Late Fee (if applicable)', 'amount' =>50];
        $dynamicForm[self::RECERTIFY_FORM_PDF]['iai']['W_FEE_INCOMPLETE'] = ['name' => 'Incomplete Application Fee (if applicable)', 'amount' =>30];

        $dynamicForm[self::RECERTIFY_FORM_PDF]['cranes'] = array();
        $dynamicForm[self::RECERTIFY_FORM_PDF]['cranes']['W_EXAM_CORE'] = ['name' => 'Core Exam', 'number' => '652605'];
        $dynamicForm[self::RECERTIFY_FORM_PDF]['cranes']['W_EXAM_LBC'] = ['name' => 'Lattice Boom Crawler (LBC)', 'number' => '652625,652608'];
        $dynamicForm[self::RECERTIFY_FORM_PDF]['cranes']['W_EXAM_LBT'] = ['name' => 'Lattice Boom Truck (LBT) ', 'number' => '652611,652635'];
        $dynamicForm[self::RECERTIFY_FORM_PDF]['cranes']['W_EXAM_TLL'] = ['name' => 'Telescopic Boom-Swing Cab (TLL)', 'number' => '652614,652645'];
        $dynamicForm[self::RECERTIFY_FORM_PDF]['cranes']['W_EXAM_TSS'] = ['name' => 'Telescopic Boom-Fixed Cab (TSS)', 'number' => '652656,652665'];
        $dynamicForm[self::RECERTIFY_FORM_PDF]['cranes']['W_EXAM_TOWER'] = ['name' => 'Tower Crane', 'number' => '654602'];
        $dynamicForm[self::RECERTIFY_FORM_PDF]['cranes']['W_EXAM_OVERHEAD'] = ['name' => 'Overhead Crane', 'number' => '653602'];
        $dynamicForm[self::RECERTIFY_FORM_PDF]['cranes']['W_EXAM_ADD_LBC'] = ['name' => 'Lattice Boom Crawler (LBC)', 'number' => '652620,652607'];
        $dynamicForm[self::RECERTIFY_FORM_PDF]['cranes']['W_EXAM_ADD_LBT'] = ['name' => 'Lattice Boom Truck (LBT)', 'number' => '652609,652610'];
        $dynamicForm[self::RECERTIFY_FORM_PDF]['cranes']['W_EXAM_ADD_TLL'] = ['name' => 'Telescopic Boom-Swing Cab (TLL)', 'number' => '652612,652613'];
        $dynamicForm[self::RECERTIFY_FORM_PDF]['cranes']['W_EXAM_ADD_TSS'] = ['name' => 'Telescopic Boom-Fixed Cab (TSS)', 'number' => '652616,652660'];
        $dynamicForm[self::RECERTIFY_FORM_PDF]['cranes']['W_EXAM_ADD_BTF'] = ['name' => 'Boom Truck-Fixed Cab (BTF)', 'number' => '652671'];
        $dynamicForm[self::RECERTIFY_FORM_PDF]['cranes']['W_EXAM_ADD_TOWER'] = ['name' => 'Tower Crane', 'number' => '654602'];
        $dynamicForm[self::RECERTIFY_FORM_PDF]['cranes']['W_EXAM_ADD_OVERHEAD'] = ['name' => 'Overhead Crane', 'number' => '653602'];

        return $dynamicForm;
    }
}
