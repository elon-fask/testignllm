<?php

use yii\db\Migration;

/**
 * Class m171228_134532_rename_candidate_company_info_columns
 */
class m171228_134532_rename_candidate_company_info_columns extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->renameColumn('candidates', 'companyName', 'company_name');
        $this->renameColumn('candidates', 'companyFax', 'company_fax');
        $this->renameColumn('candidates', 'companyPhone', 'company_phone');
        $this->renameColumn('candidates', 'companyAddress', 'company_address');
        $this->renameColumn('candidates', 'companyCity', 'company_city');
        $this->renameColumn('candidates', 'companyState', 'company_state');
        $this->renameColumn('candidates', 'companyZip', 'company_zip');
        $this->renameColumn('candidates', 'contactPerson', 'contact_person');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->renameColumn('candidates', 'company_name', 'companyName');
        $this->renameColumn('candidates', 'company_fax', 'companyFax');
        $this->renameColumn('candidates', 'company_phone', 'companyPhone');
        $this->renameColumn('candidates', 'company_address', 'companyAddress');
        $this->renameColumn('candidates', 'company_city', 'companyCity');
        $this->renameColumn('candidates', 'company_state', 'companyState');
        $this->renameColumn('candidates', 'company_zip', 'companyZip');
        $this->renameColumn('candidates', 'contact_person', 'contactPerson');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171228_134532_rename_candidate_company_info_columns cannot be reverted.\n";

        return false;
    }
    */
}
