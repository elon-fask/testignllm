<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_role`.
 */
class m180524_071905_create_user_role_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('user_role', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'role' => $this->string()->notNull(),
        ]);

        $this->createIndex(
            'idx-user_role-user_id',
            'user_role',
            'user_id'
        );

        $this->addForeignKey(
            'fk-user_role-user_id',
            'user_role',
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
            'fk-user_role-user_id',
            'user_role'
        );

        $this->dropIndex(
            'idx-user_role-user_id',
            'user_role'
        );

        $this->dropTable('user_role');
    }
}
