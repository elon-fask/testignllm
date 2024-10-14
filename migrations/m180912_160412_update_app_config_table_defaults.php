<?php

use yii\db\Migration;
use app\models\AppConfig;

/**
 * Class m180912_160412_update_app_config_table_defaults
 */
class m180912_160412_update_app_config_table_defaults extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(
            'app_config',
            'sort_order',
            $this->integer()->defaultValue(1000)->after('val')
        );

        $unfinishedRegistrationConfig = AppConfig::findOne([
            'code' => 'UNSIGNED_EMAIL_RECIPIENT'
        ]);

        if (!isset($unfinishedRegistrationConfig)) {
            $unfinishedRegistrationConfig = new AppConfig();
            $unfinishedRegistrationConfig->val = 'admin@tabletbasedtesting.com';
        }

        $unfinishedRegistrationConfig->code = 'UNFINISHED_REGISTRATION_EMAIL_RECIPIENT';
        $unfinishedRegistrationConfig->name = 'Unfinished Registration Notification Email List';
        $unfinishedRegistrationConfig->sort_order = 0;
        $unfinishedRegistrationConfig->save();

        $newCandidatesCCSConfig = AppConfig::findOne([
            'code' => 'ADMIN_NEW_CANDIDATES_EMAIL_RECIPIENT'
        ]);

        if (!isset($newCandidatesCCSConfig)) {
            $newCandidatesCCSConfig = new AppConfig();
            $newCandidatesCCSConfig->val = 'admin@tabletbasedtesting.com';
        }

        $newCandidatesCCSConfig->code = 'NEW_CANDIDATES_CCS_EMAIL_RECIPIENT';
        $newCandidatesCCSConfig->name = 'New Candidate Registration (CCS) Notification Email List';
        $newCandidatesCCSConfig->sort_order = 1;
        $newCandidatesCCSConfig->save();

        $newCandidatesACSConfig = new AppConfig();
        $newCandidatesACSConfig->val = 'admin@tabletbasedtesting.com';
        $newCandidatesACSConfig->code = 'NEW_CANDIDATES_ACS_EMAIL_RECIPIENT';
        $newCandidatesACSConfig->name = 'New Candidate Registration (ACS) Notification Email List';
        $newCandidatesACSConfig->sort_order = 2;
        $newCandidatesACSConfig->save();

        $travelFormRecipientConfig = AppConfig::findOne([
            'code' => 'TRAVEL_FORM_EMAIL_RECIPIENT'
        ]);

        if (isset($travelFormRecipientConfig)) {
            $travelFormRecipientConfig->sort_order = 3;
            $travelFormRecipientConfig->save();
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180912_160412_update_app_config_table_defaults cannot be reverted.\n";

        return false;
    }
}
