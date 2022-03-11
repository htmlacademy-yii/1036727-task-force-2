<?php

namespace anatolev\service;

abstract class TaskAction
{
    /**
     * @return string
     */
    abstract protected function getName(): string;

    /**
     * @return string
     */
    abstract protected function getInnerName(): string;

    /**
     * @param int $taskId
     * @param int $userId
     * @return bool
     */
    abstract protected function checkUserRights(int $taskId, int $userId): bool;
}
