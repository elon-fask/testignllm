<?php

use yii\db\Migration;

/**
 * Class m190523_110536_add_application_type_id_foreign_key_to_candidates_table
 */
class m190523_110536_add_application_type_id_foreign_key_to_candidates_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addForeignKey(
            'fk-candidates-application_type_id',
            'candidates',
            'application_type_id',
            'application_type',
            'id',
            'RESTRICT'
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-candidates-application_type_id',
            'candidates'
        );
    }
}
