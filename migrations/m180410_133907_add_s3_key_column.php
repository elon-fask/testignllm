<?php

use yii\db\Migration;

/**
 * Class m180410_133907_add_s3_key_column
 */
class m180410_133907_add_s3_key_column extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('candidate_session_exam_photo', 's3_key', $this->string()->notNull()->after('testSessionId'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180410_133907_add_s3_key_column cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180410_133907_add_s3_key_column cannot be reverted.\n";

        return false;
    }
    */
}
