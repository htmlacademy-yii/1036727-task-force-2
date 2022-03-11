<?php

namespace anatolev\service;

use Yii;
use app\services\TaskService;
use anatolev\service\Task;

class ActCancel extends TaskAction
{
    const NAME = 'Отменить';
    const INNER_NAME = 'act_cancel';
    const FORM_TYPE = 'cancel-form';

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
        $isCustomer = $service->isTaskCustomer($taskId, $userId);

        return $taskStatus === Task::STATUS_NEW && $isCustomer;
    }
}
