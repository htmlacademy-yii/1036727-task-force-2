<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use app\rbac\AcceptReplyRule;
use app\rbac\CancelTaskRule;
use app\rbac\CompleteTaskRule;
use app\rbac\CreateReplyRule;
use app\rbac\RefuseReplyRule;
use app\rbac\RefuseTaskRule;

class RbacController extends Controller
{
    /**
     * @return int Exit code
     */
    public function actionInit(): int
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        $createTask = $auth->createPermission('createTask');
        $auth->add($createTask);


        $rule = new CancelTaskRule();
        $auth->add($rule);

        $cancelTask = $auth->createPermission('cancelTask');
        $auth->add($cancelTask);

        $cancelOwnTask = $auth->createPermission('cancelOwnTask');
        $cancelOwnTask->ruleName = $rule->name;
        $auth->add($cancelOwnTask);
        $auth->addChild($cancelTask, $cancelOwnTask);


        $rule = new CreateReplyRule();
        $auth->add($rule);

        $createReply = $auth->createPermission('createReply');
        $auth->add($createReply);

        $createOwnReply = $auth->createPermission('createOwnReply');
        $createOwnReply->ruleName = $rule->name;
        $auth->add($createOwnReply);
        $auth->addChild($createReply, $createOwnReply);


        $rule = new AcceptReplyRule();
        $auth->add($rule);

        $acceptReply = $auth->createPermission('acceptReply');
        $auth->add($acceptReply);

        $acceptOwnReply = $auth->createPermission('acceptOwnReply');
        $acceptOwnReply->ruleName = $rule->name;
        $auth->add($acceptOwnReply);
        $auth->addChild($acceptReply, $acceptOwnReply);


        $rule = new RefuseReplyRule();
        $auth->add($rule);

        $refuseReply = $auth->createPermission('refuseReply');
        $auth->add($refuseReply);

        $refuseOwnReply = $auth->createPermission('refuseOwnReply');
        $refuseOwnReply->ruleName = $rule->name;
        $auth->add($refuseOwnReply);
        $auth->addChild($refuseReply, $refuseOwnReply);


        $rule = new CompleteTaskRule();
        $auth->add($rule);

        $completeTask = $auth->createPermission('completeTask');
        $auth->add($completeTask);

        $completeOwnTask = $auth->createPermission('completeOwnTask');
        $completeOwnTask->ruleName = $rule->name;
        $auth->add($completeOwnTask);
        $auth->addChild($completeTask, $completeOwnTask);


        $rule = new RefuseTaskRule();
        $auth->add($rule);

        $refuseTask = $auth->createPermission('refuseTask');
        $auth->add($refuseTask);

        $refuseOwnTask = $auth->createPermission('refuseOwnTask');
        $refuseOwnTask->ruleName = $rule->name;
        $auth->add($refuseOwnTask);
        $auth->addChild($refuseTask, $refuseOwnTask);


        $customer = $auth->createRole('customer');
        $auth->add($customer);
        $auth->addChild($customer, $createTask);
        $auth->addChild($customer, $cancelTask);
        $auth->addChild($customer, $acceptReply);
        $auth->addChild($customer, $refuseReply);
        $auth->addChild($customer, $completeTask);

        $executor = $auth->createRole('executor');
        $auth->add($executor);
        $auth->addChild($executor, $createReply);
        $auth->addChild($executor, $refuseTask);

        return ExitCode::OK;
    }
}
