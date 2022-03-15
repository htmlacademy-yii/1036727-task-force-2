<?php

namespace app\rbac;

use yii\rbac\Rule;
use anatolev\service\ActRefuse;

class RefuseTaskRule extends Rule
{
    public $name = 'refuseTask';

    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated width.
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        return isset($params['taskId'])
            ? (new ActRefuse())->checkUserRights($params['taskId'], $user)
            : false;
    }
}
