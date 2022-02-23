<?php

namespace anatolev\service;

class ActRefuse extends TaskAction
{
    const NAME = 'Отказаться';
    const INNER_NAME = 'act_refuse';
    const FORM_TYPE = 'refuse-form';

    public function getName(): string
    {
        return self::NAME;
    }

    public function getInnerName(): string
    {
        return self::INNER_NAME;
    }

    public function checkUserRights(int $task_id, int $customer_id, ?int $executor_id, int $user_id): bool
    {
        return $executor_id === $user_id;
    }
}
