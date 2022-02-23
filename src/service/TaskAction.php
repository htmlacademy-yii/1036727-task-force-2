<?php

namespace anatolev\service;

abstract class TaskAction
{
    /**
     * Возвращает название действия
     *
     * @return string
     */
    abstract protected function getName(): string;

    /**
     * Возвращает внутреннее имя действия
     *
     * @return string
     */
    abstract protected function getInnerName(): string;

    /**
     * Проверяет права аутентифицированного пользователя.
     * Возвращает true или false
     * (в зависимости от доступности выполнения этого действия)
     *
     * @param int $task_id id задания
     *
     * @return bool
     */
    abstract protected function checkUserRights(int $task_id): bool;
}
