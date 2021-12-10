<?php

namespace app\controllers;

use app\models\User;
use yii\web\Controller;

class MainController extends Controller
{
    public function actionIndex()
    {
        foreach (User::find()->all() as $user) {
            print($user->email . '<br>');
            print($user->city->name . '<br><br>');
        }
    }
}
