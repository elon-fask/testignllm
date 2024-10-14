<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_otp`.
 */
class m180418_152216_create_user_otp_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('user_otp', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'otp_token' => $this->string()->notNull(),
            'expires_at' => $this->dateTime()->notNull()
        ]);

        $this->createIndex(
            'idx-user_otp-user_id',
            'user_otp',
            'user_id'
        );

        $this->createIndex(
            'idx-user_otp-otp_token',
            'user_otp',
            'otp_token'
        );

        $this->addForeignKey(
            'fk-user_otp_user_id-user_user_id',
            'user_otp',
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
            'fk-user_otp_user_id-user_user_id',
            'user_otp'
        );

        $this->dropIndex(
            'idx-user_otp-otp_token',
            'user_otp'
        );

        $this->dropIndex(
            'idx-user_otp-user_id',
            'user_otp'
        );

        $this->dropTable('user_otp');
    }
}
