<?php

use yii\db\Migration;

/**
 * Handles adding photo_s3_key to table `candidates`.
 */
class m180403_082118_add_photo_s3_key_column_to_candidates_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('candidates', 'photo_s3_key', $this->string()->after('email'));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('candidates', 'photo_s3_key');
    }
}
