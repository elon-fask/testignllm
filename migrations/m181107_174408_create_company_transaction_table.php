<?php

use yii\db\Migration;

/**
 * Handles the creation of table `company_transaction`.
 */
class m181107_174408_create_company_transaction_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('company_transaction', [
            'id' => $this->primaryKey(),
            'company_id' => $this->integer()->notNull(),
            'amount' => $this->decimal(65, 2)->notNull(),
            'type' => $this->string()->notNull(),
            'transaction_id' => $this->string(),
            'auth_code' => $this->string(),
            'check_number' => $this->string(),
            'posted_by' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull()
        ]);

        $this->createIndex(
            'idx-company_transaction-company_id',
            'company_transaction',
            'company_id'
        );

        $this->addForeignKey(
            'fk-company_transaction-company_id',
            'company_transaction',
            'company_id',
            'company',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-company_transaction-posted_by',
            'company_transaction',
            'posted_by',
            'user',
            'id',
            'CASCADE'
        );

        $this->createTable('company_transaction_candidate_transaction', [
            'id' => $this->primaryKey(),
            'company_transaction_id' => $this->integer()->notNull(),
            'candidate_transaction_id' => $this->integer()->notNull()
        ]);

        $this->createIndex(
            'idx-co_tx_ca_tx-company_transaction_id',
            'company_transaction_candidate_transaction',
            'company_transaction_id'
        );

        $this->addForeignKey(
            'fk-co_tx_ca_tx-company_transaction_id',
            'company_transaction_candidate_transaction',
            'company_transaction_id',
            'company_transaction',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-co_tx_ca_tx-candidate_transaction_id',
            'company_transaction_candidate_transaction',
            'candidate_transaction_id'
        );

        $this->addForeignKey(
            'fk-co_tx_ca_tx-candidate_transaction_id',
            'company_transaction_candidate_transaction',
            'candidate_transaction_id',
            'candidate_transactions',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey(
            'fk-co_tx_ca_tx-candidate_transaction_id',
            'company_transaction_candidate_transaction'
        );

        $this->dropIndex(
            'idx-co_tx_ca_tx-candidate_transaction_id',
            'company_transaction_candidate_transaction'
        );

        $this->dropForeignKey(
            'fk-co_tx_ca_tx-company_transaction_id',
            'company_transaction_candidate_transaction'
        );

        $this->dropIndex(
            'idx-co_tx_ca_tx-company_transaction_id',
            'company_transaction_candidate_transaction'
        );

        $this->dropTable('company_transaction_candidate_transaction');

        $this->dropForeignKey(
            'fk-company_transaction-company_id',
            'company_transaction'
        );

        $this->dropIndex(
            'idx-company_transaction-company_id',
            'company_transaction'
        );

        $this->dropTable('company_transaction');
    }
}
