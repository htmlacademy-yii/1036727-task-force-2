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
     * Проверяет права пользователя.
     * Возвращает true или false
     * (в зависимости от доступности выполнения этого действия)
     *
     * @param int $executor_id id исполнителя задания
     * @param int $customer_id id заказчика задания
     * @param int $user_id id текущего пользователя
     *
     * @return bool
     */
    abstract protected function checkUserRights(int $executor_id, int $customer_id, int $user_id): bool;
}
