<?php

use yii\db\Migration;
use app\models\User;
use app\models\UserRole;

/**
 * Class m180821_134153_migrate_user_staff_type_to_user_role_table
 */
class m180821_134153_migrate_user_staff_type_to_user_role_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $users = User::find()->all();

        foreach ($users as $user) {
            if (isset(UserRole::STAFF_TYPE_MAPPING[$user->staffType])) {
                $role = UserRole::findOne([
                    'user_id' => $user->id,
                    'role' => UserRole::STAFF_TYPE_MAPPING[$user->staffType]
                ]);

                if (!isset($role)) {
                    $newRole = new UserRole();
                    $newRole->user_id = $user->id;
                    $newRole->role = UserRole::STAFF_TYPE_MAPPING[$user->staffType];
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
        echo "m180821_134153_migrate_user_staff_type_to_user_role_table cannot be reverted.\n";

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180821_134153_migrate_user_staff_type_to_user_role_table cannot be reverted.\n";

        return false;
    }
    */
}
