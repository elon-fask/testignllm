<?php

use yii\db\Migration;

/**
 * Handles the creation of table `pending_transaction`.
 */
class m180327_123200_create_pending_transaction_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('pending_transaction', [
            'id' => $this->primaryKey(),
            'posted_by' => $this->integer()->notNull(),
            'candidate_id' => $this->integer()->notNull(),
            'amount' => $this->decimal(65, 2)->notNull(),
            'type' => $this->integer()->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime()
        ]);

        $this->addForeignKey(
            'fk-pending_transaction-candidate_id',
            'pending_transaction',
            'candidate_id',
            'candidates',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-pending_transaction-user_id',
            'pending_transaction',
            'posted_by',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('pending_transaction');
    }
}
