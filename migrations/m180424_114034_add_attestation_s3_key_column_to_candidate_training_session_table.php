<?php

use yii\db\Migration;

/**
 * Handles adding attestation_s3_key to table `candidate_training_session`.
 */
class m180424_114034_add_attestation_s3_key_column_to_candidate_training_session_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('candidate_training_session', 'attestation_s3_key', $this->string()->after('end_time'));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->addColumn('candidate_training_session', 'attestation_s3_key');
    }
}
