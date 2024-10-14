<?php

use yii\db\Migration;

/**
 * Handles adding candidate_checklist to table `candidates`.
 */
class m180405_075307_add_candidate_checklist_columns_to_candidates_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('candidates', 'signed_w_form_received', $this->dateTime());
        $this->addColumn('candidates', 'signed_p_form_received', $this->dateTime());
        $this->addColumn('candidates', 'confirmation_email_last_sent', $this->dateTime());
        $this->addColumn('candidates', 'app_form_sent_to_nccco', $this->dateTime());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('candidates', 'signed_w_form_received');
        $this->dropColumn('candidates', 'signed_p_form_received');
        $this->dropColumn('candidates', 'confirmation_email_last_sent');
        $this->dropColumn('candidates', 'app_form_sent_to_nccco');
    }
}
