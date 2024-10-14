<?php

use yii\db\Migration;
use app\models\Checklist;
use app\models\ChecklistItems;

class m160613_113048_add_written_calendar_checklist extends Migration
{
    public function up()
    {
        $checklistItem = ChecklistItems::findOne(['name' => 'Finished Pre-checklist']);
        $checklistItem->name = 'Check for any outstanding applications';
        $checklistItem->save();
        
        $checklistItem = ChecklistItems::findOne(['name' => 'Finished Stationery Checklist']);
        $checklistItem->name = 'Attempt to resolve/address reported issues at the site (discrepancy report)';
        $checklistItem->save();
        
        $checklistItem = ChecklistItems::findOne(['name' => 'Submitted Applications to IAI']);
        $checklistItem->name = 'Generate and send class readiness packet';
        $checklistItem->save();
        
        $checklistItem = ChecklistItems::findOne(['name' => 'Printed Class Readiness Report']);
        $checklistItem->name = 'Confirm site has necessary supplies';
        $checklistItem->save();
        
        $checkList = new Checklist();
        $checkList->name = 'Written Calendar Checklist';
        $checkList->type = Checklist::TYPE_WRITTEN_CALENDAR_CHECKLIST;
        $checkList->isArchived = 0;
        $checkList->save();
        
        //we create the list item
        $checkListItem = new ChecklistItems();
        $checkListItem->name = 'Check for any outstanding applications';
        $checkListItem->checklistId = $checkList->id;
        $checkListItem->isArchived = 0;
        $checkListItem->save();
        
        $checkListItem = new ChecklistItems();
        $checkListItem->name = 'Generate and send class readiness packet';
        $checkListItem->checklistId = $checkList->id;
        $checkListItem->isArchived = 0;
        $checkListItem->save();
        
        $checkListItem = new ChecklistItems();
        $checkListItem->name = 'Confirm site has necessary supplies';
        $checkListItem->checklistId = $checkList->id;
        $checkListItem->isArchived = 0;
        $checkListItem->save();        
    }

    public function down()
    {
        echo "m160613_113048_add_written_calendar_checklist cannot be reverted.\n";

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
