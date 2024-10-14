<?php

use yii\db\Migration;

/**
 * Class m181211_144036_add_upcoming_class_report_recipient_app_config
 */
class m181211_144036_add_upcoming_class_report_recipient_app_config extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('app_config', [
            'code' => 'UPCOMING_CLASS_REPORT_EMAIL_RECIPIENT',
            'name' => 'Upcoming Class Report Notification Email List',
            'val' => 'admin@tabletbasedtesting.com',
            'sort_order' => 4
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m181211_144036_add_upcoming_class_report_recipient_app_config cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181211_144036_add_upcoming_class_report_recipient_app_config cannot be reverted.\n";

        return false;
    }
    */
}
