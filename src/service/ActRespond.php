<?php

namespace anatolev\service;

use app\services\UserService;
use app\services\ReplyService;

class ActRespond extends TaskAction
{
    const NAME = 'Откликнуться';
    const INNER_NAME = 'act_respond';
    const FORM_TYPE = 'respond-form';

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
        $cond1 = (new ReplyService())->exist($task_id, $user_id);
        $cond2 = (new UserService())->isExecutor($user_id);

        return !$cond1 && $cond2;
    }
}
