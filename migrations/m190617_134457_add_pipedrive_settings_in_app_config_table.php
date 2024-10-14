<?php

use yii\db\Migration;

/**
 * Class m190617_134457_add_pipedrive_settings_in_app_config_table
 */
class m190617_134457_add_pipedrive_settings_in_app_config_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('app_config', [
            'code' => 'PIPEDRIVE_INITIAL_STAGE',
            'name' => 'PipeDrive Initial Stage',
            'val' => '',
            'sort_order' => 6
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m190617_134457_add_pipedrive_settings_in_app_config_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190617_134457_add_pipedrive_settings_in_app_config_table cannot be reverted.\n";

        return false;
    }
    */
}
