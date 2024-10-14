<?php

use yii\db\Migration;

/**
 * Class m180405_125850_add_instructor_notes_column_in_candidates_table
 */
class m180405_125850_add_instructor_notes_column_in_candidates_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('candidates', 'instructor_notes', $this->string());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('candidates', 'instructor_notes');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180405_125850_add_instructor_notes_column_in_candidates_table cannot be reverted.\n";

        return false;
    }
    */
}
