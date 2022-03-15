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
    public function checkUserRights(int $taskId, int $userId): bool
    {
        $statusId = (new TaskService())->getStatusId($taskId);
        $isExecutor = (new UserService())->isExecutor($userId);
        $replyExist = (new ReplyService())->exist($taskId, $userId);

        return !$replyExist && $statusId === Task::STATUS_NEW_ID;
    }
}
