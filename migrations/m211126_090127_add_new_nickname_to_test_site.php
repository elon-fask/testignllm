<?php

use yii\db\Migration;

/**
 * Class m211126_090127_add_new_nickname_to_test_site
 */
class m211126_090127_add_new_nickname_to_test_site extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('test_site', 'nickname', $this->string(255)->after('address'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m211126_090127_add_new_nickname_to_test_site cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211126_090127_add_new_nickname_to_test_site cannot be reverted.\n";

        return false;
    }
    */
}
