<?php

use yii\db\Migration;

/**
 * Class m180614_114627_alter_comment_column_in_travel_form_table
 */
class m180614_114627_alter_comment_column_in_travel_form_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn('travel_form', 'comment', $this->text());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180614_114627_alter_comment_column_in_travel_form_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180614_114627_alter_comment_column_in_travel_form_table cannot be reverted.\n";

        return false;
    }
    */
}
