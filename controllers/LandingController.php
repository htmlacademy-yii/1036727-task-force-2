<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class LandingController extends Controller
{
    public $layout = 'landing';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'denyCallback' => function ($rule, $action) {
                    $this->redirect(['tasks/index']);
                },
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['?']
                    ]
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }
}
