<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;
use app\models\forms\AddTaskForm;
use app\models\forms\SearchForm;
use app\services\TaskService;
use app\services\CategoryService;
use app\services\UserService;

class TasksController extends SecuredController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'actions' => ['create'],
                        'matchCallback' => function ($rule, $action) {
                            return (new UserService())->isCustomer(Yii::$app->user->id);
                        }
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'actions' => ['index', 'view']
                    ]
                ]
            ]
        ];
    }

    public function actionCreate()
    {
        $addTaskForm = new AddTaskForm();

        if (Yii::$app->request->isPost) {
            $addTaskForm->load(Yii::$app->request->post());
            $addTaskForm->files = UploadedFile::getInstances($addTaskForm, 'files');

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                return ActiveForm::validate($addTaskForm);
            }

            if ($addTaskForm->validate()) {
                $taskId = (new TaskService())->create($addTaskForm);
                $this->redirect(['tasks/view', 'id' => $taskId]);
            }
        }

        $categories = (new CategoryService())->findAll();

        return $this->render('add', [
            'model' => $addTaskForm,
            'categories' => $categories
        ]);
    }

    public function actionIndex(?string $category = null)
    {
        $searchForm = new SearchForm();
        $tasks = [];

        if (Yii::$app->request->isPost) {
            $searchForm->load(Yii::$app->request->post());

        } elseif (isset($category)) {

            if ($id = (new CategoryService())->getByInnerName($category)?->id) {
                $searchForm->categories[] = $id;
            }
        }

        if ($searchForm->validate()) {
            $tasks = (new TaskService())->getFilteredTasks($searchForm);
        }

        $categories = (new CategoryService())->findAll();

        return $this->render('index', [
            'model' => $searchForm,
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
