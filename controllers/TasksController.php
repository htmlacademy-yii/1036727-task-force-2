<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
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
                        'roles' => ['createTask'],
                        'actions' => ['create']
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'actions' => ['index', 'user-tasks', 'view']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['cancel'],
                        'roles' => ['cancelOwnTask'],
                        'roleParams' => ['id' => Yii::$app->request->get('id', 0)]
                    ],
                    [
                        'allow' => true,
                        'actions' => ['complete'],
                        'roles' => ['completeOwnTask'],
                        'roleParams' => ['id' => Yii::$app->request->post('CompleteForm')['task_id'] ?? 0]
                    ],
                    [
                        'allow' => true,
                        'actions' => ['refuse'],
                        'roles' => ['refuseOwnTask'],
                        'roleParams' => ['id' => Yii::$app->request->get('id', 0)]
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

    public function actionCancel(int $id)
    {
        (new TaskService())->cancel($id);

        return $this->redirect(['tasks/view', 'id' => $id]);
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

    public function actionUserTasks()
    {
        $filter = Yii::$app->request->get('filter');
        $userId = Yii::$app->user->id;

        if ((new UserService())->isExecutor($userId)) {
            $tasks = (new TaskService())->getExecutorTasks($userId, $filter);
        } else {
            $tasks = (new TaskService())->getCustomerTasks($userId, $filter);
        }

        return $this->render('user-tasks', [
            'tasks' => $tasks,
            'filter' => $filter,
        ]);
    }

    public function actionRefuse(int $id)
    {
        (new TaskService())->refuse($id);

        return $this->redirect(['tasks/view', 'id' => $id]);
    }

    // разбить на 2 метода
    public function actionIndex(?string $category = null)
    {
        // Yii::$app->authManager->assign(Yii::$app->authManager->getRole('customer'), 7);
        // var_dump(Yii::$app->user->can('refuseOwnReply', ['replyId' => 4]));
        // exit;
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
            throw new NotFoundHttpException();
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
