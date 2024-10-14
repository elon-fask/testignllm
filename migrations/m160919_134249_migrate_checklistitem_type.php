<?php

use yii\db\Migration;
use app\models\ChecklistItems;
use app\models\Checklist;

class m160919_134249_migrate_checklistitem_type extends Migration
{
    public function up()
    {
        $items = ChecklistItems::find()->where('')->all();
        foreach($items as $item){
            $checkList = Checklist::findOne($item->checklistId);
            if($checkList->type == Checklist::TYPE_WRITTEN){
                $item->itemType = ChecklistItems::TYPE_NUMBER;
                $item->save();
            }else if($checkList->type == Checklist::TYPE_POST || $checkList->type == Checklist::TYPE_PRE){
                $item->itemType = ChecklistItems::TYPE_PASS_FAIL;
                $item->save();
            }
        }
    }

    public function down()
    {
        echo "m160919_134249_migrate_checklistitem_type cannot be reverted.\n";

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
