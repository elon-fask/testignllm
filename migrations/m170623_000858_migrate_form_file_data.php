<?php

use yii\db\Migration;
use app\models\ApplicationFormFile;

class m170623_000858_migrate_form_file_data extends Migration
{
    public function safeUp()
    {
        $writtenFormFile = new ApplicationFormFile();
        $writtenFormFile->filename = 'iai-blank-written-test-site-application-new-candidate.pdf';
        $writtenFormFile->name = 'Candidate Application WRITTEN EXAMINATION—MOBILE, TOWER, & OVERHEAD CRANE OPERATOR';
        $writtenFormFile->archived = 0;
        $writtenFormFile->save();

        $writtenCCFormFile = new ApplicationFormFile();
        $writtenCCFormFile->filename = 'iai-blank-written-test-site-application-new-candidate-credit-card.pdf';
        $writtenCCFormFile->name = 'Candidate Application WRITTEN EXAMINATION—MOBILE, TOWER, & OVERHEAD CRANE OPERATOR (with credit card)';
        $writtenCCFormFile->archived = 0;
        $writtenCCFormFile->save();

        $practicalFormFile = new ApplicationFormFile();
        $practicalFormFile->filename = 'iai-blank-practical-test-application-form.pdf';
        $practicalFormFile->name = 'Candidate Application PRACTICAL EXAMINATION—MOBILE, TOWER, & OVERHEAD CRANE OPERATOR';
        $practicalFormFile->archived = 0;
        $practicalFormFile->save();

        $recertFormFile = new ApplicationFormFile();
        $recertFormFile->filename = 'iai-blank-recert-with-1000-hours-application.pdf';
        $recertFormFile->name = 'Recertification Application WRITTEN EXAMINATION—MOBILE, TOWER, & OVERHEAD CRANE OPERATOR';
        $recertFormFile->archived = 0;
        $recertFormFile->save();

        $recertCCFormFile = new ApplicationFormFile();
        $recertCCFormFile->filename = 'iai-blank-recert-with-1000-hours-application-credit-card.pdf';
        $recertCCFormFile->name = 'Recertification Application WRITTEN EXAMINATION—MOBILE, TOWER, & OVERHEAD CRANE OPERATOR (with credit card)';
        $recertCCFormFile->archived = 0;
        $recertCCFormFile->save();
    }

    public function safeDown()
    {
        echo "m170623_000858_migrate_form_file_data cannot be reverted.\n";

        return false;
    }
}
