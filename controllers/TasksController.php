<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\forms\SearchForm;
use app\services\TaskService;
use app\services\CategoryService;

class TasksController extends SecuredController
{
    public function actionIndex(?string $category = null)
    {
        $model = new SearchForm();
        $tasks = [];

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());

        } elseif (isset($category)) {

            if ($id = (new CategoryService())->getByInnerName($category)?->id) {
                $model->categories[] = $id;
            }
        }

        if ($model->validate()) {
            $tasks = (new TaskService())->getFilteredTasks($model);
        }

        $categories = (new CategoryService())->getAllCategories();

        return $this->render('index', [
            'model' => $model,
            'tasks' => $tasks,
            'categories' => $categories,
            'period_values' => SearchForm::PERIOD_VALUES
        ]);
    }

    public function actionView(int $id)
    {
        if (!$task = (new TaskService())->getTaskById($id)) {
            throw new NotFoundHttpException;
        }

        return $this->render('view', [
            'task' => $task
        ]);
    }
}
