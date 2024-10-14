<?php

use yii\db\Migration;

class m160602_125140_add_checklist extends Migration
{
    public function up()
    {
        $this->execute("
         CREATE TABLE checklist
         (
             id int(11) NOT NULL auto_increment,
             name varchar(250) not null,    
             type int(11) not null,
    	     isArchived int(11) null default 0,
             date_created datetime default null,
             PRIMARY KEY (id)
         );
    	
            
         CREATE TABLE checklist_items
         (
             id int(11) NOT NULL auto_increment,
             checklistId int(11) not null,            
             name varchar(250) not null,  
             description text null,
             status int(11) null default 0,
    	     isArchived int(11) null default 0,
             date_created datetime default null,
             PRIMARY KEY (id),
             CONSTRAINT fk_checklist_items_checklistId FOREIGN KEY (checklistId)
    			      REFERENCES checklist (id) MATCH SIMPLE
    			      ON UPDATE CASCADE ON DELETE CASCADE
         );
            
         alter table test_site add column preChecklistId int(11) null;
         alter table test_site add column postChecklistId int(11) null;

         alter table test_session add column preChecklistId int(11) null;
         alter table test_session add column postChecklistId int(11) null;

         CREATE TABLE test_session_checklist_items
         (
             id int(11) NOT NULL auto_increment,
             testSessionId int(11) not null,
             checkListItemId int(11) null,   
             status int(11) null default 0,         
             type int(11) not null,
    	     date_created datetime default null,
             PRIMARY KEY (id),
             CONSTRAINT fk_test_session_checklist_items_checkListItemId FOREIGN KEY (checkListItemId)
    			      REFERENCES checklist_items (id) MATCH SIMPLE
    			      ON UPDATE CASCADE ON DELETE CASCADE,
            CONSTRAINT fk_test_session_checklist_items_testSessionId FOREIGN KEY (testSessionId)
    			      REFERENCES test_session (id) MATCH SIMPLE
    			      ON UPDATE CASCADE ON DELETE CASCADE
         );
            
         CREATE TABLE test_session_checklist_notes
         (
             id int(11) NOT NULL auto_increment,
             testSessionChecklistItemId int(11) not null,
             note text null,
             created_by int(11) not null,
    	     date_created datetime default null,
             PRIMARY KEY (id),
             CONSTRAINT fk_test_session_checklist_notes_testSessionChecklistItemId FOREIGN KEY (testSessionChecklistItemId)
    			      REFERENCES test_session_checklist_items (id) MATCH SIMPLE
    			      ON UPDATE CASCADE ON DELETE CASCADE,
            CONSTRAINT fk_test_session_checklist_notes_created_by FOREIGN KEY (created_by)
    			      REFERENCES user (id) MATCH SIMPLE
    			      ON UPDATE CASCADE ON DELETE CASCADE
         );
            
            
         ");

    }

    public function down()
    {
        echo "m160602_125140_add_checklist cannot be reverted.\n";

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
