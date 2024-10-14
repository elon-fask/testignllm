<?php

use yii\db\Migration;

/**
 * Class m180612_085141_add_type_column_candidate_training_session_table
 */
class m180612_085141_add_type_column_candidate_training_session_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('candidate_training_session', 'type', $this->string()->notNull()->defaultValue('NA')->after('test_session_id'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('candidate_training_session', 'type');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180612_085141_add_type_column_candidate_training_session_table cannot be reverted.\n";

        return false;
    }
    */
}
