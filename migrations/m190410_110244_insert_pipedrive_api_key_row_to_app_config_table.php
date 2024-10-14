<?php

use yii\db\Migration;

/**
 * Class m190410_110244_insert_pipedrive_api_key_row_to_app_config_table
 */
class m190410_110244_insert_pipedrive_api_key_row_to_app_config_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('app_config', [
            'code' => 'PIPEDRIVE_API_KEY',
            'name' => 'PipeDrive API Key',
            'val' => '',
            'sort_order' => 5
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m190410_110244_insert_pipedrive_api_key_row_to_app_config_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190410_110244_insert_pipedrive_api_key_row_to_app_config_table cannot be reverted.\n";

        return false;
    }
    */
}
