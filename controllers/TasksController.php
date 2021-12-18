<?php

namespace app\controllers;

use yii\web\Controller;
use app\services\TaskService;
use app\services\CategoryService;
use app\models\forms\SearchForm;

class TasksController extends Controller
{
    public function actionIndex()
    {
        $model = new SearchForm();

        if (\Yii::$app->request->getIsPost()) {
            $model->load(\Yii::$app->request->post());
        }

        $tasks = (new TaskService())->getFilteredTasks($model);
        $categories = (new CategoryService())->getAllCategories();

        return $this->render('index', [
            'model' => $model,
            'tasks' => $tasks,
            'categories' => $categories
        ]);
    }
}
