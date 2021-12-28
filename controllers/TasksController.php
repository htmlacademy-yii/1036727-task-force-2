<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\forms\SearchForm;
use app\services\TaskService;
use app\services\CategoryService;

class TasksController extends Controller
{
    public function actionIndex()
    {
        $model = new SearchForm();

        if (Yii::$app->request->getIsPost()) {
            $model->load(Yii::$app->request->post());

            if ($model->validate()) {
                $tasks = (new TaskService())->getFilteredTasks($model);
            }
        }

        isset($tasks) or $tasks = (new TaskService())->getAllTasks();

        $categories = (new CategoryService())->getAllCategories();

        return $this->render('index', [
            'model' => $model,
            'tasks' => $tasks,
            'categories' => $categories,
            'period_values' => SearchForm::PERIOD_VALUES
        ]);
    }
}
