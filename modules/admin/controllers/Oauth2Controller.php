<?php

namespace app\modules\admin\controllers;

use app\models\UserOauth2Request;
use app\models\UserOauth2Token;
use app\helpers\QuickBooksOnlineHelper;

class Oauth2Controller extends CController
{
    public function actionLink($provider)
    {
        $userId = null;
        try {
            $userId = \Yii::$app->user->identity->id;
        } catch (Exception $e) {
            throw new \yii\web\BadRequestHttpException('User is not signed in.');
        }

        if ($provider === 'QUICKBOOKS_ONLINE') {
            $authUrl = QuickBooksOnlineHelper::createAuthUrl($userId);
            return $this->redirect($authUrl);
        }

        throw new \yii\web\BadRequestHttpException('Invalid OAuth2 Provider.');
    }

    public function actionRegisterToken($provider)
    {
        if ($provider === 'QUICKBOOKS_ONLINE') {
            $fields = \Yii::$app->request->get();
            $reqFields = ['state', 'code', 'realmId'];

            foreach ($reqFields as $field) {
                if (!$fields[$field]) {
                    throw new \yii\web\BadRequestHttpException('Missing field ' . $field . '.');
                }
            }

            $userId = null;
            try {
                $userId = \Yii::$app->user->identity->id;
            } catch (Exception $e) {
                throw new \yii\web\BadRequestHttpException('User is not signed in.');
            }

            $request = UserOauth2Request::findOne([
                'user_id' => $userId,
                'state' => $fields['state']
            ]);

            if (!$request) {
                throw new \yii\web\NotFoundHttpException('OAuth2 Request not found.');
            }

            $qboToken = QuickBooksOnlineHelper::exchangeAuthCodeForToken($fields['code'], $fields['realmId']);

            if (isset($qboToken)) {
                $token = new UserOauth2Token();
                $token->user_id = $request->user_id;
                $token->provider = $request->provider;
                $token->scope = 'com.intuit.quickbooks.accounting';
                $token->realm_id = $qboToken->getRealmID();
                $token->access_token = $qboToken->getAccessToken();
                $token->access_token_expires_at = $qboToken->getAccessTokenExpiresAt();
                $token->refresh_token = $qboToken->getRefreshToken();
                $token->refresh_token_expires_at = $qboToken->getRefreshTokenExpiresAt();
                $token->token_type = 'bearer';

                if ($token->save()) {
                    $request->delete();
                    return $this->redirect('/admin/staff/update?id=' . $userId);
                } else {
                    throw new \yii\web\ServerErrorHttpException('Could not receive token from Intuit. Please try again.');
                }
            }
        }

        return $this->redirect('/admin/staff/update?id=' . $userId);
    }
}
