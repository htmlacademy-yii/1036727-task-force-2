<?php

namespace anatolev\service;

class ActCancel extends TaskAction
{
    const NAME = 'Отменить';
    const INNER_NAME = 'act_cancel';
    const FORM_TYPE = 'cancel-form';

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
        return $customer_id === $user_id;
    }
}
