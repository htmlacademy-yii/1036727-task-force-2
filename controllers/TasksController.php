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
        }

        $tasks = (new TaskService())->getFilteredTasks($model);
        $categories = (new CategoryService())->getAllCategories();
        
        $period_values = [
            '0' => 'default',
            '1' => '1 час',
            '12' => '12 часов',
            '24' => '24 часа'
        ];

        return $this->render('index', [
            'model' => $model,
            'tasks' => $tasks,
            'categories' => $categories,
            'period_values' => $period_values
        ]);
    }
}
