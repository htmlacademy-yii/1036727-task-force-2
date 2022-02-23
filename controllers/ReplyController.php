<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
// use yii\web\Response;
// use yii\widgets\ActiveForm;
use app\models\forms\ResponseForm;
use app\services\ReplyService;
use app\services\TaskService;
use app\services\UserService;
use anatolev\service\Task;
use anatolev\helpers\TaskHelper;

class ReplyController extends Controller
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
                        'actions' => ['accept', 'refuse'],
                        'matchCallback' => function ($rule, $action) {
                            $reply_id = Yii::$app->request->get('reply_id');
                            $task = (new ReplyService())->findOne($reply_id)?->task;

                            return $task && TaskHelper::isActual($task);
                        }
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'actions' => ['create'],
                        'matchCallback' => function ($rule, $action) {
                            $task_id = Yii::$app->request->post('ResponseForm')['task_id'];
                            $taskStatus = (new TaskService())->getStatus($task_id);
                            $isExecutor = (new UserService())->isExecutor(Yii::$app->user->id);

                            return $taskStatus === Task::STATUS_NEW && $isExecutor;
                        }
                    ]
                ]
            ]
        ];
    }

    public function actionCreate()
    {
        $responseForm = new ResponseForm();

        if (Yii::$app->request->isPost) {
            $responseForm->load(Yii::$app->request->post());

            if ($responseForm->validate()) {
                (new ReplyService())->create($responseForm);
                $task_id = Yii::$app->request->post('ResponseForm')['task_id'];

                return $this->redirect(['tasks/view', 'id' => $task_id]);
            }
        }
    }

    public function actionAccept(int $reply_id)
    {
        $task_id = (new ReplyService())->accept($reply_id);

        $this->redirect(['tasks/view', 'id' => $task_id]);
    }

    public function actionRefuse(int $reply_id)
    {
        $task_id = (new ReplyService())->refuse($reply_id);

        $this->redirect(['tasks/view', 'id' => $task_id]);
    }
}
