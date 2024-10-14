<?php

use yii\db\Migration;

/**
 * Handles the creation of table `company`.
 */
class m181106_035413_create_company_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('company', [
            'id' => $this->primaryKey(),
            'qbo_id' => $this->integer(),
            'name' => $this->string(),
            'email' => $this->string(),
            'phone' => $this->string(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('company');
    }
}
