<?php

use yii\db\Migration;

/**
 * Class m180423_123211_rename_test_session_photos_table
 */
class m180423_123211_rename_test_session_photos_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->renameColumn('test_session_photos', 'testSessionId', 'test_session_id');
        $this->renameColumn('test_session_photos', 'savedBy', 'uploaded_by');
        $this->addColumn('test_session_photos', 's3_key', $this->string()->notNull()->after('test_session_id'));
        $this->dropColumn('test_session_photos', 'filename');
        $this->renameTable('test_session_photos', 'test_session_photo');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180423_123211_rename_test_session_photos_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180423_123211_rename_test_session_photos_table cannot be reverted.\n";

        return false;
    }
    */
}
