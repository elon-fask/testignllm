<?php

use yii\db\Migration;
use app\models\User;
use app\models\UserRole;

/**
 * Class m180823_094629_migrate_admin_users_to_user_role_table
 */
class m180823_094629_migrate_admin_users_to_user_role_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $users = User::find()->all();

        foreach ($users as $user) {
            if ($user->role === User::ROLE_ADMIN) {
                $role = UserRole::findOne([
                    'user_id' => $user->id,
                    'role' => UserRole::SUPER_ADMIN
                ]);

                if (!isset($role)) {
                    $newRole = new UserRole();
                    $newRole->user_id = $user->id;
                    $newRole->role = UserRole::SUPER_ADMIN;
                    $newRole->save();
                }
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180823_094629_migrate_admin_users_to_user_role_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180823_094629_migrate_admin_users_to_user_role_table cannot be reverted.\n";

        return false;
    }
    */
}
