<?php

use yii\db\Migration;

/**
 * Class m180110_182737_add_travel_form_email_recipient_setting
 */
class m180110_182737_add_travel_form_email_recipient_setting extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('app_config', [
            'code' => 'TRAVEL_FORM_EMAIL_RECIPIENT',
            'name' => 'Travel Form Notification Recipient List',
            'val' => 'admin@tabletbasedtesting.com'
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->delete('app_config', [
            'code' => 'TRAVEL_FORM_EMAIL_RECIPIENT'
        ]);
    }
}
