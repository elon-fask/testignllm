<?php
namespace app\modules\api\controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use yii\rest\ActiveController;
use app\models\Candidates;
use Yii;




class AuthController extends ActiveController
{
    public function init()
    {
        parent::init();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    }

    public function behaviors()
    {
        return [
            'corsFilter' => [
                'class' => \yii\filters\Cors::className(),
                'cors' => [
                    'Origin' => ["*"],
                    'Access-Control-Request-Headers' => ['*'],
                ],
            ],
        ];
    }

    public $modelClass = 'app\models\Candidates';

    public function actionLogin()
    {
        $request = \Yii::$app->request;
        if (!$request->isPost) {
            return ['status' => 'ERROR', 'message' => 'Only POST request allowed!'];
        }

        $postData = $request->post();
        $candidate = Candidates::findOne(['email'=>$postData['email'], 'phone' => $postData['phone']]);

        if (!$candidate) {
            return ['status' => 'ERROR', 'message' => 'Invalide payload!'];
        }

        $publicKey = file_get_contents(Yii::$app->basePath . Yii::$app->params['jwt_public_key_path']);
        $privateKey = file_get_contents(Yii::$app->basePath . Yii::$app->params['jwt_private_key_path']);

        $payload = [
            'iss' => 'http://example.org',
            'aud' => 'http://example.com',
            'iat' => strtotime('now'),
            'exp' => strtotime('now + 2 hours'),
            'role' => 'candidate',
        ];

        $jwt = JWT::encode($payload, $privateKey, 'RS256');

        return [
            'status'=>'OK', 
            'jwt' => $jwt,
        ];
    }
}
