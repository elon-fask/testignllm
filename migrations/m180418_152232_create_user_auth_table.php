<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_auth`.
 */
class m180418_152232_create_user_auth_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('user_auth', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'auth_token' => $this->string()->notNull(),
            'description' => $this->string(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime()
        ]);

        $this->createIndex(
            'idx-user_auth-user_id',
            'user_auth',
            'user_id'
        );

        $this->createIndex(
            'idx-user_auth-auth_token',
            'user_auth',
            'auth_token'
        );

        $this->addForeignKey(
            'fk-user_auth_user_id-user_user_id',
            'user_auth',
            'user_id',
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
        $this->dropForeignKey(
            'fk-user_auth_user_id-user_user_id',
            'user_otp'
        );

        $this->dropIndex(
            'idx-user_auth-auth_token',
            'user_otp'
        );

        $this->dropIndex(
            'idx-user_auth-user_id',
            'user_otp'
        );

        $this->dropTable('user_auth');
    }
}
