<?php

use yii\db\Migration;

/**
 * Handles the creation of table `travel_form`.
 */
class m180109_185552_create_travel_form_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('travel_form', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'destination_loc' => $this->string()->notNull(),
            'destination_date' => $this->date()->notNull(),
            'destination_time' => $this->string()->notNull(),
            'return_loc' => $this->string()->notNull(),
            'return_date' => $this->date()->notNull(),
            'return_time' => $this->string()->notNull(),
            'hotel_required' => $this->boolean()->notNull(),
            'car_rental_required' => $this->boolean()->notNull(),
            'comment' => $this->string(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('travel_form');
    }
}
