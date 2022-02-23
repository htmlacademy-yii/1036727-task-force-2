<?php

namespace anatolev\service;

use Yii;
use app\services\TaskService;
use app\services\UserService;
use app\services\ReplyService;

class ActRespond extends TaskAction
{
    const NAME = 'Откликнуться';
    const INNER_NAME = 'act_respond';
    const FORM_TYPE = 'respond-form';

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * {@inheritdoc}
     */
    public function getInnerName(): string
    {
        return self::INNER_NAME;
    }

    /**
     * {@inheritdoc}
     */
    public function checkUserRights(int $task_id): bool
    {
        $taskStatus = (new TaskService())->getStatus($task_id);
        $isExecutor = (new UserService())->isExecutor(Yii::$app->user->id);
        $replyExist = (new ReplyService())->exist($task_id, Yii::$app->user->id);

        return !$replyExist && $taskStatus === Task::STATUS_NEW && $isExecutor;
    }
}
