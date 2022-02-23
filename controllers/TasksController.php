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
use app\models\forms\CompleteForm;
use app\models\forms\ResponseForm;
use app\services\CategoryService;
use app\services\TaskService;
use app\services\UserService;

use anatolev\service\ActCancel;
use anatolev\service\ActDone;
use anatolev\service\ActRefuse;
use anatolev\service\Task;

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
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'actions' => ['cancel'],
                        'matchCallback' => function ($rule, $action) {
                            $id = Yii::$app->request->get('task_id', 0);

                            return (new Task($id))->getAvailableAction() instanceof ActCancel;
                        }
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'actions' => ['complete'],
                        'matchCallback' => function ($rule, $action) {
                            $id = Yii::$app->request->post('CompleteForm')['task_id'] ?? 0;

                            return (new Task($id))->getAvailableAction() instanceof ActDone;
                        }
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'actions' => ['refuse'],
                        'matchCallback' => function ($rule, $action) {
                            $id = Yii::$app->request->get('task_id', 0);

                            return (new Task($id))->getAvailableAction() instanceof ActRefuse;
                        }
                    ],
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

                return $this->redirect(['tasks/view', 'id' => $taskId]);
            }
        }

        $categories = (new CategoryService())->findAll();

        return $this->render('add', [
            'model' => $addTaskForm,
            'categories' => $categories
        ]);
    }

    public function actionCancel(int $task_id)
    {
        (new TaskService())->cancel($task_id);

        return $this->redirect(['tasks/view', 'id' => $task_id]);
    }

    public function actionComplete()
    {
        $completeForm = new CompleteForm();

        if (Yii::$app->request->isPost) {
            $completeForm->load(Yii::$app->request->post());

            if ($completeForm->validate()) {
                (new TaskService())->complete($completeForm);

                return $this->redirect(['tasks/view', 'id' => $completeForm->task_id]);
            }
        }
    }

    public function actionRefuse(int $task_id)
    {
        (new TaskService())->refuse($task_id);

        return $this->redirect(['tasks/view', 'id' => $task_id]);
    }

    // разбить на 2 метода
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
        if (!$task = (new TaskService())->findOne($id)) {
            throw new NotFoundHttpException;
        }

        $completeForm = new CompleteForm();
        $responseForm = new ResponseForm();

        $availableAction = (new Task($id))->getAvailableAction();

        return $this->render('view', [
            'task' => $task,
            'completeForm' => $completeForm,
            'responseForm' => $responseForm,
            'availableAction' => $availableAction
        ]);
    }
}
