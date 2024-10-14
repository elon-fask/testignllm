<?php
namespace app\models;

use Yii;

/**
 * This is the model class for table "messages".
 *
 * @property integer $id
 * @property string $sender_id
 * @property string $receiver_id
 * @property string $subject
 * @property string $body
 * @property string $is_read
 * @property string $deleted_by
 * @property string $deleted_at
 * @property string $created_at
 * @property integer $sender_delete
 * @property integer $sender_permanent_delete
 * @property integer $receiver_delete
 * @property integer $receiver_permanent_delete
 */
class Messages extends \yii\db\ActiveRecord
{

    const DELETED_BY_RECEIVER = 'receiver';

    const DELETED_BY_SENDER = 'sender';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'messages';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'sender_id',
                    'receiver_id'
                ],
                'required'
            ],
            [
                [
                    'body'
                ],
                'string'
            ],
            [
                [
                    'deleted_at',
                    'created_at'
                ],
                'safe'
            ],
            [
                [
                    'sender_delete',
                    'sender_permanent_delete',
                    'receiver_delete',
                    'receiver_permanent_delete'
                ],
                'integer'
            ],
            [
                [
                    'sender_id',
                    'receiver_id',
                    'subject'
                ],
                'string',
                'max' => 256
            ],
            [
                [
                    'is_read',
                    'deleted_by'
                ],
                'string',
                'max' => 20
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
            'sender_id' => 'Sender ID',
            'receiver_id' => 'Receiver ID',
            'subject' => 'Subject',
            'body' => 'Body',
            'is_read' => 'Is Read',
            'deleted_by' => 'Deleted By',
            'deleted_at' => 'Deleted At',
            'created_at' => 'Created At',
            'sender_delete' => 'Sender Delete',
            'sender_permanent_delete' => 'Sender Permanent Delete',
            'receiver_delete' => 'Receiver Delete',
            'receiver_permanent_delete' => 'Receiver Permanent Delete'
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->created_at = date('Y-m-d H:i:s', strtotime('now'));
            return true;
        } else {
            return false;
        }
    }

    
    public static function getUserMessage($userId, $resultsPerPage , $page, $showUnreadOnly = false)
    {        
        
        $resp = array();
        $prefixQuery = '';
        
        if($showUnreadOnly == true){
            $prefixQuery = 'is_read != 1 and';
        }
        $resp['list'] = Messages::find()->where($prefixQuery.' receiver_delete = 0 and receiver_id = '.$userId.' order by created_at desc limit '.$resultsPerPage.' offset '.(($page-1)*$resultsPerPage))->all();
        $resp['count'] = Messages::find()->where($prefixQuery.' receiver_delete = 0 and receiver_id = '.$userId)->count();
        
        return $resp;
        
    }
    public static function getAdapterForInbox($userId, $limit = '', $showOnlyUnread = false)
    {
        $sql = "select *   from messages where receiver_id = " . $userId . " and receiver_delete = 0 ";
        if ($showOnlyUnread) {
            $sql .= ' and is_read = 0 ';
        }
        $sql .= " order by created_at DESC";
        $command = \Yii::$app->db->createCommand($sql);
        $results = $command->queryAll();
        return $results;
    }

    public static function getAdapterForSent($userId)
    {
        $sql = "select *  from messages where sender_id = " . $userId . " and sender_delete = 0 order by created_at DESC";
        $command = \Yii::$app->db->createCommand($sql);
        $results = $command->queryAll();
        return $results;
    }

    public static function getAdapterForDeleted($userId)
    {
        $sql = "select *  from messages t where (t.receiver_id = " . $userId . " and t.receiver_delete = 1 and t.receiver_permanent_delete = 0) or (t.sender_id = " . $userId . " and t.sender_delete = 1 and t.sender_permanent_delete = 0) order by t.created_at DESC";
        $command = \Yii::$app->db->createCommand($sql);
        $results = $command->queryAll();
        return $results;
    }

    public function deleteByUser($userId)
    {
        if (! $userId) {
            return false;
        }
        if ($this->receiver_id == $userId) {
            if ($this->receiver_delete == 1) {
                $this->receiver_permanent_delete = 1;
            }
            $this->receiver_delete = 1;
            $this->deleted_by = self::DELETED_BY_RECEIVER;
            $this->deleted_at = Date('Y-m-d H:i:s');
            $this->save(false);
            
           // return true;
        }
        
        if ($this->sender_id == $userId) {
            
            if ($this->sender_delete == 1) {
                $this->sender_permanent_delete = 1;
            }
            $this->sender_delete = 1;
            $this->deleted_at = Date('Y-m-d H:i:s');
            $this->save(false);
            // }
            
           // return true;
        }
        
        // message was not deleted
        return true;
    }

    public function markAsRead()
    {
        if (! $this->is_read) {
            $this->is_read = true;
            $this->save(false);
        }
    }

    public static function getCountUnreaded($userId)
    {
        $sql = "select * from messages where receiver_id = " . $userId . " and receiver_delete = 0 and is_read = 0";
        
        $command = \Yii::$app->db->createCommand($sql);
        $results = $command->queryAll();
        return count($results);
    }
}
