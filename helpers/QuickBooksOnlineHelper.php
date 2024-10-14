<?php
namespace app\helpers;

use QuickBooksOnline\API\DataService\DataService;
use \Yii;
use app\models\User;
use app\models\UserOauth2Request;
use app\models\UserOauth2Token;

class QuickBooksOnlineHelper
{
    public static function createAuthUrl($userId)
    {
        $dataService = DataService::Configure([
            'auth_mode' => 'oauth2',
            'ClientID' => Yii::$app->params['qbo.client.id'],
            'ClientSecret' => Yii::$app->params['qbo.client.secret'],
            'RedirectURI' => 'http://cso.craneadmin.test/admin/oauth2/register-token?provider=QUICKBOOKS_ONLINE',
            'scope' => 'com.intuit.quickbooks.accounting',
            'baseUrl' => 'Development'
        ]);

        $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
        $authorizationUrl = $OAuth2LoginHelper->getAuthorizationCodeURL();

        $request = new UserOauth2Request();
        $request->user_id = $userId;
        $request->provider = 'QUICKBOOKS_ONLINE';
        $request->state = $OAuth2LoginHelper->getState();
        $request->prev_route = \yii\helpers\Url::base();

        if ($request->save()) {
            return $authorizationUrl;
        }

        return false;
    }

    public static function exchangeAuthCodeForToken($authCode, $realmId)
    {
        $dataService = DataService::Configure([
            'auth_mode' => 'oauth2',
            'ClientID' => Yii::$app->params['qbo.client.id'],
            'ClientSecret' => Yii::$app->params['qbo.client.secret'],
            'RedirectURI' => 'http://cso.craneadmin.test/admin/oauth2/register-token?provider=QUICKBOOKS_ONLINE',
            'scope' => 'com.intuit.quickbooks.accounting',
            'baseUrl' => 'Development'
        ]);

        $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
        $accessToken = $OAuth2LoginHelper->exchangeAuthorizationCodeForToken($authCode, $realmId);

        return $accessToken;
    }

    public static function prepareToken($oAuthToken)
    {
        $dataService = DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => Yii::$app->params['qbo.client.id'],
            'ClientSecret' => Yii::$app->params['qbo.client.secret'],
            'accessTokenKey' => $oAuthToken->access_token,
            'refreshTokenKey' => $oAuthToken->refresh_token,
            'QBORealmID' => $oAuthToken->realm_id,
            'baseUrl' => 'Development'
        ));

        $now = new \DateTime();
        $accessTokenExpireDateTime = new \DateTime($oAuthToken->access_token_expires_at);
        $accessTokenExpired = $accessTokenExpireDateTime->getTimestamp() - $now->getTimestamp() < 0;

        if ($accessTokenExpired) {
            $refreshTokenExpireDateTime = new \DateTime($oAuthToken->refresh_token_expires_at);
            $refreshTokenExpired = $refreshTokenExpireDateTime->getTimestamp() - $now->getTimestamp() < 0;

            if ($refreshTokenExpired) {
                throw new \yii\web\UnauthorizedHttpException('OAuth Token expired, please re-link account.');
            }

            $oAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
            $newAccessTokenObj = $oAuth2LoginHelper->refreshAccessTokenWithRefreshToken($oAuthToken->refresh_token);

            $oAuthToken->access_token = $newAccessTokenObj->getAccessToken();
            $oAuthToken->access_token_expires_at = $newAccessTokenObj->getAccessTokenExpiresAt();
            $oAuthToken->refresh_token = $newAccessTokenObj->getRefreshToken();
            $oAuthToken->refresh_token_expires_at = $newAccessTokenObj->getRefreshTokenExpiresAt();

            if (!$oAuthToken->save()) {
                throw new \yii\web\ServerErrorHttpException('OAuth Token could not be refreshed, please re-link account.');
            }
        }

        return $dataService;
    }

    public static function query($query)
    {
        $oAuthToken = UserOauth2Token::findOne([
            'user_id' => \Yii::$app->user->identity->id,
            'provider' => 'QUICKBOOKS_ONLINE'
        ]);

        if (!isset($oAuthToken)) {
            throw new \yii\web\ForbiddenHttpException('User not signed in or unauthorized to connect to QuickBooks Online');
        }

        $dataService = self::prepareToken($oAuthToken);
        $dataService->throwExceptionOnError(true);
        $queryResult = $dataService->query($query);

        return $queryResult;
    }
}
