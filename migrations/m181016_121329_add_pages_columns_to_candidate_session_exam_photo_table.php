<?php

use yii\db\Migration;

/**
 * Handles adding pages to table `candidate_session_exam_photo`.
 */
class m181016_121329_add_pages_columns_to_candidate_session_exam_photo_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('candidate_session_exam_photo', 'page_num', $this->integer()->after('s3_key'));
        $this->addColumn('candidate_session_exam_photo', 'page_type', $this->string()->after('page_num'));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('candidate_session_exam_photo', 'page_type');
        $this->dropColumn('candidate_session_exam_photo', 'page_num');
    }
}
