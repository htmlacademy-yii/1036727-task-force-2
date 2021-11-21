<?php
namespace Anatolev\Service;

class ActCancel extends TaskAction
{
    private const NAME = 'Отменить';
    private const INNER_NAME = 'act_cancel';

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
        return $customer_id === $user_id;
    }
}
