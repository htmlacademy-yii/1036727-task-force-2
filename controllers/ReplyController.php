<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\forms\ResponseForm;
use app\services\ReplyService;
use app\services\TaskService;
use anatolev\service\ActRespond;
use anatolev\service\Task;

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
                            $replyId = Yii::$app->request->get('reply_id', 0);

                            return (new TaskService())->isActual($replyId);
                        }
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'actions' => ['create'],
                        'matchCallback' => function ($rule, $action) {
                            $id = Yii::$app->request->post('ResponseForm')['task_id'] ?? 0;

                            return (new Task($id))->getAvailableAction() instanceof ActRespond;
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
                $task_id = (new ReplyService())->create($responseForm);

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
