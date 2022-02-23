<?php

namespace anatolev\service;

use Yii;
use app\services\TaskService;
use anatolev\service\Task;

class ActDone extends TaskAction
{
    const NAME = 'Выполнено';
    const INNER_NAME = 'act_done';
    const FORM_TYPE = 'complete-form';

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
        $service = new TaskService();
        $taskStatus = $service->getStatus($task_id);
        $isCustomer = $service->isTaskCustomer($task_id, Yii::$app->user->id);

        return $taskStatus === Task::STATUS_WORK && $isCustomer;
    }
}
