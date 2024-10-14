<?php

use yii\db\Migration;

/**
 * Class m181030_131350_create_qbo_oauth2_tables
 */
class m181030_131350_create_qbo_oauth2_tables extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('user_oauth2_request', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'provider' => $this->string()->notNull(),
            'state' => $this->string()->notNull(),
            'prev_route' => $this->string(),
            'created_at' => $this->dateTime()->notNull()
        ]);

        $this->createIndex(
            'idx-oauth2_req-state',
            'user_oauth2_request',
            'state'
        );

        $this->createIndex(
            'idx-oauth2_req-user_id',
            'user_oauth2_request',
            'user_id'
        );

        $this->addForeignKey(
            'fk-oauth2_req-user_id',
            'user_oauth2_request',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        $this->createTable('user_oauth2_token', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'provider' => $this->string()->notNull(),
            'scope' => $this->string()->notNull(),
            'realm_id' => $this->string(),
            'access_token' => $this->text()->notNull(),
            'access_token_expires_at' => $this->dateTime()->notNull(),
            'refresh_token' => $this->text(),
            'refresh_token_expires_at' => $this->dateTime(),
            'token_type' => $this->string(),
            'created_at' => $this->dateTime()->notNull()
        ]);

        $this->createIndex(
            'idx-oauth2_token-user_id',
            'user_oauth2_token',
            'user_id'
        );

        $this->addForeignKey(
            'fk-oauth2_token-user_id',
            'user_oauth2_token',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m181030_131350_create_qbo_oauth2_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181030_131350_create_qbo_oauth2_tables cannot be reverted.\n";

        return false;
    }
    */
}
