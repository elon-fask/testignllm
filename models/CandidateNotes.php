<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "candidate_notes".
 *
 * @property integer $id
 * @property integer $candidate_id
 * @property integer $user_id
 * @property string $notes
 * @property string $date_created
 * @property string $date_updated
 *
 * @property Candidates $candidate
 * @property User $user
 */
class CandidateNotes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'candidate_notes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['candidate_id', 'user_id', 'notes'], 'required'],
            [['candidate_id', 'user_id'], 'integer'],
            [['notes'], 'string'],
            [['date_created', 'date_updated'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'candidate_id' => 'Candidate ID',
            'user_id' => 'User ID',
            'notes' => 'Notes',
            'date_created' => 'Date Created',
            'date_updated' => 'Date Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCandidate()
    {
        return $this->hasOne(Candidates::className(), ['id' => 'candidate_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->date_updated=date('Y-m-d', strtotime('now'));
            if ($this->isNewRecord) {
                $this->date_created=date('Y-m-d', strtotime('now'));
            }
            return true;
        } else {
            return false;
        }
    }

    public function getSummary($limit=100)
    {
        $str = $this->notes;
        if (strlen ($str) > $limit) {
            $str = substr ($str, 0, $limit - 3);
            return (substr ($str, 0, strrpos ($str, ' ')) . '...');
        }
        return trim($str);
    }
}
