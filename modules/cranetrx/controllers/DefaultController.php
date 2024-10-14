<?php

namespace app\modules\cranetrx\controllers;

use yii\web\Controller;

/**
 * Default controller for the `mugshot` module
 */
class DefaultController extends Controller
{
    public function beforeAction($event)
    {
        $this->layout = '@app/modules/cranetrx/views/layouts/main';
        return parent::beforeAction($event);
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
