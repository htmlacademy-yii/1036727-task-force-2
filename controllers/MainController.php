<?php

namespace app\controllers;

use app\models\TaskStatus;
use yii\web\Controller;

class MainController extends Controller
{
    public function actionIndex()
    {
        foreach (TaskStatus::find()->all() as $status) {
            print($status->name . '<br>');
            print($status->inner_name . '<br><br>');
        }
    }
}
