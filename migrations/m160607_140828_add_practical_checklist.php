<?php

use yii\db\Migration;
use app\models\Checklist;
use app\models\ChecklistItems;

class m160607_140828_add_practical_checklist extends Migration
{
    public function up()
    {
        $checkList = new Checklist();
        $checkList->name = 'Practical Checklist';
        $checkList->type = Checklist::TYPE_PRACTICAL;
        $checkList->isArchived = 0;
        $checkList->save(false);
        
        //we create the list item
        $checkListItem = new ChecklistItems();
        $checkListItem->name = 'Finished Pre-checklist';
        $checkListItem->checklistId = $checkList->id;
        $checkListItem->isArchived = 0;
        $checkListItem->save(false);        
        
        $checkListItem = new ChecklistItems();
        $checkListItem->name = 'Finished Stationery Checklist';
        $checkListItem->checklistId = $checkList->id;
        $checkListItem->isArchived = 0;
        $checkListItem->save(false);
        
        $checkListItem = new ChecklistItems();
        $checkListItem->name = 'Submitted Applications to IAI';
        $checkListItem->checklistId = $checkList->id;
        $checkListItem->isArchived = 0;
        $checkListItem->save(false);
        
        $checkListItem = new ChecklistItems();
        $checkListItem->name = 'Printed Class Readiness Report';
        $checkListItem->checklistId = $checkList->id;
        $checkListItem->isArchived = 0;
        $checkListItem->save(false);
    }

    public function down()
    {
        echo "m160607_140828_add_practical_checklist cannot be reverted.\n";

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
