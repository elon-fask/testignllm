<?php

use yii\db\Migration;

/**
 * Handles adding details to table `pending_transaction`.
 */
class m181204_045803_add_details_columns_to_pending_transaction_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('pending_transaction', 'check_number', $this->integer()->after('type'));
        $this->addColumn('pending_transaction', '3rd_party_tx_id', $this->string()->after('check_number'));
        $this->addColumn('pending_transaction', '3rd_party_auth_code', $this->string()->after('3rd_party_tx_id'));

        $this->createTable('pending_transaction_line_item', [
            'id' => $this->primaryKey(),
            'tx_id' => $this->integer()->notNull(),
            'description' => $this->string()->notNull(),
            'amount' => $this->decimal(65, 2)->notNull()
        ]);

        $this->createIndex(
            'idx-pending_tx_line_item_tx_id',
            'pending_transaction_line_item',
            'tx_id'
        );

        $this->addForeignKey(
            'fk-pending_tx_line_item_tx_id',
            'pending_transaction_line_item',
            'tx_id',
            'pending_transaction',
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
            'fk-pending_tx_line_item_tx_id',
            'pending_transaction_line_item'
        );

        $this->dropIndex(
            'idx-pending_tx_line_item_tx_id',
            'pending_transaction_line_item'
        );

        $this->dropTable('pending_transaction_line_item');

        $this->dropColumn('pending_transaction', '3rd_party_auth_code');
        $this->dropColumn('pending_transaction', '3rd_party_tx_id');
        $this->dropColumn('pending_transaction', 'check_number');
    }
}
