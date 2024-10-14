<?php

namespace app\helpers;

use app\models\User;

class UserHelper {

    static public function isAdmin() {
        if(\Yii::$app->session->get('role') == User::ROLE_ADMIN)
            return true;
        return false;
    }
}
