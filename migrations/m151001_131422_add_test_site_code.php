<?php

use yii\db\Schema;
use yii\db\Migration;
use app\models\TestSite;
use app\helpers\UtilityHelper;

class m151001_131422_add_test_site_code extends Migration
{
    public function up()
    {
        $this->execute("
            alter table test_site add column uniqueCode varchar(250) null;        
         ");
        //$this->addColumn('test_site', 'nickname', $this->string(255)->after('address'));
        $testSites = TestSite::find()->where('')->all();
        foreach($testSites as $site){
            $code = UtilityHelper::generateUniqueCodeForTestSite();
            $site->uniqueCode = $code;
            $site->save();
        }
    }

    public function down()
    {
        echo "m151001_131422_add_test_site_code cannot be reverted.\n";

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
