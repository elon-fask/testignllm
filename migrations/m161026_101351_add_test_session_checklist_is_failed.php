<?php

use yii\db\Migration;
use app\models\TestSessionChecklistItems;
use app\models\ChecklistItems;

class m161026_101351_add_test_session_checklist_is_failed extends Migration
{
    public function up()
    {
        $this->execute("
            alter table test_session_checklist_items add column isFailed int(11) null default 0;");
        
        $testSessionChecklistItems = TestSessionChecklistItems::find()->where('')->all();
        
        
        foreach($testSessionChecklistItems as $item){
            $checklistItem = ChecklistItems::findOne($item->checkListItemId);
            //we check if failed
            
            if($checklistItem->itemType == ChecklistItems::TYPE_PASS_FAIL){
                if($item->status == ChecklistItems::STATUS_FAIL){
                   $item->isFailed = 1;
                   $item->save();
                }else{
                    $item->isFailed = 0;
                    $item->save();
                }
            }else if($checklistItem->itemType == ChecklistItems::TYPE_RATE_CONDITION && $checklistItem->failingScore != null){
                if($item->status != null && intval($item->status) <= intval($checklistItem->failingScore)){
                    $item->isFailed = 1;
                    $item->save();
                }else{
                    $item->isFailed = 0;
                    $item->save();
                }
            }else if($checklistItem->itemType == ChecklistItems::TYPE_RATE_FULLNESS){
                if($item->status != null && intval($item->status) <= intval($checklistItem->failingScore)){
                    $item->isFailed = 1;
                    $item->save();
                }else{
                    $item->isFailed = 0;
                    $item->save();
                }
            }
        }
        
    }

    public function down()
    {
        echo "m161026_101351_add_test_session_checklist_is_failed cannot be reverted.\n";

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
