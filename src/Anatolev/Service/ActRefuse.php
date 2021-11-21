<?php
namespace Anatolev\Service;

class ActRefuse extends TaskAction
{
    private const NAME = 'Отказаться';
    private const INNER_NAME = 'act_refuse';

    public function getName(): string
    {
        return self::NAME;
    }

    public function getInnerName(): string
    {
        return self::INNER_NAME;
    }

    public function checkUserRights(int $executor_id, int $customer_id, int $user_id): bool
    {
        return $executor_id === $user_id;
    }
}
