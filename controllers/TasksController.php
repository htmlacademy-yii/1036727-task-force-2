<?php

namespace app\controllers;

use app\models\Task;
use yii\web\Controller;

class TasksController extends Controller
{
    public function actionIndex()
    {
        $query = Task::find()
            ->joinWith('category')
            ->where(['status_id' => 1])
            ->orderBy('dt_add DESC');

        $tasks = $query->all();

        return $this->render('index', ['tasks' => $tasks]);
    }
}
