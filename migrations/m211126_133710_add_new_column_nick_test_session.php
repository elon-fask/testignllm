<?php

use yii\db\Migration;

/**
 * Class m211126_133710_add_new_column_nick_test_session
 */
class m211126_133710_add_new_column_nick_test_session extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {

        $this->addColumn('test_session', 'nick_id', $this->string(255)->after('test_site_id'));

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m211126_133710_add_new_column_nick_test_session cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211126_133710_add_new_column_nick_test_session cannot be reverted.\n";

        return false;
    }
    */
}
