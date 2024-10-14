<?php

use yii\db\Migration;

class m160603_130225_add_test_site_discrepancy extends Migration
{
    public function up()
    {
        $this->execute("
         
         CREATE TABLE test_site_checklist_item_discrepancy
         (
             id int(11) NOT NULL auto_increment,
             testSiteId int(11) not null,
             checklistItemId int(11) not null,             
             isCleared int(11) null default 0,
    	     cleared_by int(11) null,
             date_created datetime default null,
             PRIMARY KEY (id),
             CONSTRAINT fk_test_site_checklist_item_discrepancy_testSiteId FOREIGN KEY (testSiteId)
    			      REFERENCES test_site (id) MATCH SIMPLE
    			      ON UPDATE CASCADE ON DELETE CASCADE,
            CONSTRAINT fk_test_site_checklist_item_discrepancy_checklistItemId FOREIGN KEY (checklistItemId)
    			      REFERENCES checklist_items (id) MATCH SIMPLE
    			      ON UPDATE CASCADE ON DELETE CASCADE,
            CONSTRAINT fk_test_site_checklist_item_discrepancy_cleared_by FOREIGN KEY (cleared_by)
    			      REFERENCES user (id) MATCH SIMPLE
    			      ON UPDATE CASCADE ON DELETE CASCADE
         );
        
        
         ");
        
    }

    public function down()
    {
        echo "m160603_130225_add_test_site_discrepancy cannot be reverted.\n";

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
