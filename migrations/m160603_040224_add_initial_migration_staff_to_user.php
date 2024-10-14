<?php

use yii\db\Migration;
use app\models\Staff;
use app\models\User;

/**
 * Handles adding initial_migration_staff to table `user`.
 */
class m160603_040224_add_initial_migration_staff_to_user extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->execute("         
         alter table user add column staffType int(11) null;
         alter table user add column email varchar(250) null;
         alter table user add column fax varchar(250) null;        
         ");
        
        
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
    }
}
