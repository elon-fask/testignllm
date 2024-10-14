<?php
namespace app\modules\admin\controllers;

use Yii;
use app\models\Messages;
use app\models\User;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Response;

class MessagingController extends CController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [[
                    'actions' => ['viewpage', 'send','read','reply','delete', 'unread', 'index','activity', 'statistics', 'inbox', 'sent', 'deleted', 'messagedelete', 'sendmessage', 'newmessage', 'searchuser', 'messageread', 'messagereply', 'messagesend'],
                    'allow' => true,
                    'roles' => ['@'],
                ]],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => ['delete' => ['post']]
            ]
        ];
    }

    public function actionIndex()
    {
        $unread = Messages::getCountUnreaded(\Yii::$app->user->id);
        $messageIdToRead = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

        return $this->render('index', [
            'unread' => $unread,
            'messageIdToRead' => $messageIdToRead
        ]);
    }

    public function actionViewpage()
    {
        $inDashboard = isset($_REQUEST['inDashboard']) && $_REQUEST['inDashboard'] == 1 ? true : false;
        $page = $_REQUEST['page'];
        $userId = $_REQUEST['userId'];
        $items = Messages::getUserMessage($userId, 10, $page, $inDashboard);

        return $this->renderPartial('../widgets/inbox', ['items' => $items, 'currentPage' => $page]);
    }

    public function actionUnread()
    {
        $unread = Messages::getCountUnreaded(base64_decode($_REQUEST['currentUserKey']));
        $result = [];
        $result['count'] = $unread;

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $result;
    }

    public function actionNewmessage()
    {
        $messages = [];
        return $this->renderPartial('new', [
            'senderKey' => base64_decode($_REQUEST['currentUserKey'])
        ], false, true);
    }

    public function actionInbox()
    {
        $limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : '';
        $showOnlyUnread = false;

        if ($_REQUEST['isWidget'] == 1) {
            $showOnlyUnread = true;
        }

        $messages = Messages::getAdapterForInbox(
            base64_decode($_REQUEST['currentUserKey']),
            $limit,
            $showOnlyUnread
        );

        return $this->renderPartial('inbox', [
            'messages' => $messages,
            'isWidget' => $_REQUEST['isWidget']
        ], false, true);
    }

    public function actionSent()
    {
        $messages = Messages::getAdapterForSent(base64_decode($_REQUEST['currentUserKey']));
        return $this->renderPartial('sent', ['messages' => $messages], false, true);
    }

    public function actionDeleted()
    {
        $messages = Messages::getAdapterForDeleted(base64_decode($_REQUEST['currentUserKey']));
        return $this->renderPartial('deleted', ['messages' => $messages], false, true);
    }

    public function actionDelete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $messageIds = $_POST['ids'];
            $currentUserKey = base64_decode($_REQUEST['currentUserKey']);

            foreach ($messageIds as $messageId) {
                $message = Messages::findOne(['id' => $messageId]);
                $message->deleteByUser($currentUserKey);
            }
        }
    }

    public function actionReply()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $messageId = $_POST['id'];
            $message = Messages::findOne(['id' => $messageId]);
            return $this->renderPartial('replyMessage', ['message' => $message], false, true);
        }
    }

    public function actionRead()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $messageId = $_POST['id'];
            $message = Messages::findOne(['id' => $messageId]);
            $message->markAsRead();
            $showReply = false;
            $showDelete = false;

            if ($message->receiver_id == base64_decode($_REQUEST['currentUserKey'])) {
                $showReply = true;
                $showDelete = true;
                
                if (isset($message->deleted_by)) {
                    $showDelete = false;
                }
            } elseif ($message->sender_id == base64_decode($_REQUEST['currentUserKey'])) {
                $showReply = false;
                $showDelete = false;
            }

            return $this->renderPartial('read', [
                'message' => $message,
                'showReply' => $showReply,
                'showDelete' => $showDelete
            ], false, true);
        }
    }

    public function actionSearchuser()
    {
        $param = $_GET['q'];
        $model = new User();
        $results = $model->getSuggest($param);
        $result = [];
        foreach ($results as $rec) {
            $result[] = [
                'fullname' => $rec['first_name'] . ' ' . $rec['last_name'],
                'id' => $rec['id']
            ];
        }
        echo json_encode($result);
    }

    public function actionSend()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fromPid = $_REQUEST['from_pid'];
            $toPidArray = $_REQUEST['to_pid'];
            $messageBody = $_REQUEST['message_body'];
            $subject = $_REQUEST['subject'];

            $hasError = false;
            $response = [];
            $index = 0;

            foreach ($toPidArray as $toPid) {
                $hasError = false;
                $newMessage = new Messages();
                $newMessage->sender_id = $fromPid;
                $newMessage->receiver_id = $toPid;
                $newMessage->subject = $subject;
                $newMessage->body = $messageBody;
                $newMessage->save(false);
            }
        }
    }
}
