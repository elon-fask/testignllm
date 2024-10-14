<?php

use yii\db\Schema;
use yii\db\Migration;
use app\models\AppConfig;

class m160512_105921_update_iai_student_range extends Migration
{
    public function up()
    {
        $appConfig = AppConfig::findOne(['code' => AppConfig::IAI_FEE_LESS_12]);
        $appConfig->name = 'IAI Fee (1-10 Student)';
        $appConfig->save();
        
        $appConfig = AppConfig::findOne(['code' => AppConfig::IAI_FEE_LESS_15]);
        $appConfig->name = 'IAI Fee (11-15 Student)';
        $appConfig->save();
    }

    public function down()
    {
        echo "m160512_105921_update_iai_student_range cannot be reverted.\n";

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
