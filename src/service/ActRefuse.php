<?php

namespace anatolev\service;

use Yii;
use app\services\TaskService;
use anatolev\service\Task;

class ActRefuse extends TaskAction
{
    const NAME = 'Отказаться';
    const INNER_NAME = 'act_refuse';
    const FORM_TYPE = 'refuse-form';

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
        $service = new TaskService();
        $taskStatus = $service->getStatus($taskId);
        $isExecutor = $service->isTaskExecutor($taskId, $userId);

        return $taskStatus === Task::STATUS_WORK && $isExecutor;
    }
}
