<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "travel_form".
 *
 * @property int $id
 * @property boolean $completed
 * @property string $name
 * @property boolean $one_way
 * @property string $starting_location
 * @property string $destination_loc
 * @property string $desination_date
 * @property string $destination_time
 * @property string $return_loc
 * @property string $return_date
 * @property string $return_time
 * @property int $hotel_required
 * @property int $car_rental_required
 * @property string $comment
 * @property string $notes
 * @property string $created_at
 * @property string $updated_at
 */
class TravelForm extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'travel_form';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'one_way', 'destination_loc', 'destination_date', 'destination_time', 'hotel_required', 'car_rental_required'], 'required'],
            [['return_loc', 'return_date', 'return_time'], 'required', 'when' => function($travelForm) {
                return !$travelForm->one_way;
            }],
            [['completed', 'name', 'one_way', 'starting_location', 'destination_loc', 'destination_date', 'destination_time', 'return_loc', 'return_date', 'return_time', 'hotel_required', 'car_rental_required', 'comment', 'notes'], 'safe'],
            [['completed', 'one_way', 'hotel_required', 'car_rental_required'], 'boolean'],
            [['destination_date', 'return_date'], 'date', 'format' => 'yyyy-MM-dd'],
            [['name', 'starting_location', 'destination_loc', 'return_loc', 'notes'], 'string', 'max' => 255],
            [['comment'], 'string', 'max' => 2000],
            [['destination_time', 'return_time'], 'in', 'range' => ['6am - 8am', '8am - 10am', '10am - 12pm', '12pm - 2pm', '2pm - 4pm', '4pm - 6pm', '6pm - 8pm', '8pm - 10pm', '6am - 10am', '10am - 2pm', '2pm - 6pm', '6pm - 10pm']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'value' => new \yii\db\Expression('NOW()'),
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'completed' => 'Completed',
            'name' => 'Name',
            'one_way' => 'One Way Only',
            'starting_location' => 'Starting Airport Location',
            'destination_loc' => 'Destination Location',
            'destination_date' => 'Destination Date',
            'destination_time' => 'Destination Time',
            'return_loc' => 'Return Location',
            'return_date' => 'Return Date',
            'return_time' => 'Return Time',
            'hotel_required' => 'Hotel Required',
            'car_rental_required' => 'Car Rental Required',
            'comment' => 'Comment',
            'notes' => 'Notes',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getFiles()
    {
        return $this->hasMany(TravelFormFile::className(), ['travel_form_id' => 'id']);
    }
}
