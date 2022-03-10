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
                        'actions' => ['accept'],
                        'roles' => ['acceptOwnReply'],
                        'roleParams' => ['id' => Yii::$app->request->get('id', 0)],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['refuse'],
                        'roles' => ['refuseOwnReply'],
                        'roleParams' => ['id' => Yii::$app->request->get('id', 0)],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['createOwnReply'],
                        'roleParams' => [
                            'taskId' => Yii::$app->request->post('ResponseForm')['task_id'] ?? 0
                        ],
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
                $taskId = (new ReplyService())->create($responseForm);

                return $this->redirect(['tasks/view', 'id' => $taskId]);
            }
        }
    }

    public function actionAccept(int $id)
    {
        $taskId = (new ReplyService())->accept($id);

        return $this->redirect(['tasks/view', 'id' => $taskId]);
    }

    public function actionRefuse(int $id)
    {
        $taskId = (new ReplyService())->refuse($id);

        return $this->redirect(['tasks/view', 'id' => $taskId]);
    }
}
