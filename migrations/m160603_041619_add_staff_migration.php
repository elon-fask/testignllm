<?php

use yii\db\Migration;
use app\models\User;
use app\models\Staff;
use app\models\TestSession;

class m160603_041619_add_staff_migration extends Migration
{
    public function up()
    {
        $allStaffs = Staff::find()->all();
        
        foreach($allStaffs as $staff){
            //we migrate it to user
            $user = new User();
            $user->first_name = $staff->firstName;
            $user->last_name = $staff->lastName;
            $user->workPhone = $staff->phone;
            $user->fax = $staff->fax;
            $user->email = $staff->email;
            $user->role = User::ROLE_USER;
            $user->staffType = $staff->staffType;
            $user->active = $staff->archived == 1 ? 0 : 1;
            if($user->save(false)){
                TestSession::updateAll(['staff_id' => $user->id], ['staff_id' => $staff->id]);
                TestSession::updateAll(['instructor_id' => $user->id], ['instructor_id' => $staff->id]);
                TestSession::updateAll(['test_coordinator_id' => $user->id], ['test_coordinator_id' => $staff->id]);
            }            
        }
    }

    public function down()
    {
        echo "m160603_041619_add_staff_migration cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
