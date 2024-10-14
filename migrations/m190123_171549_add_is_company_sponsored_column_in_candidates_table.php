<?php

use yii\db\Migration;

/**
 * Class m190123_171549_add_is_company_sponsored_column_in_candidates_table
 */
class m190123_171549_add_is_company_sponsored_column_in_candidates_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('candidates', 'is_company_sponsored', $this->boolean()->after('retakeType'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->removeColumn('candidates', 'is_company_sponsored');
    }
}
