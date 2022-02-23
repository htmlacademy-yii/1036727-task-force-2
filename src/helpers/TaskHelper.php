<?php

namespace anatolev\helpers;

use Yii;
use app\models\Task;

class TaskHelper
{
    public static function isActual(Task $task): bool
    {
        return strtotime($task->expire ?? date('Y-m-d')) >= strtotime('today');
    }

    public static function getRepliesHeader(Task $task, int $repliesCount): string
    {
        $isCustomer = $task->customer_id === Yii::$app->user->id;

        return $isCustomer ? "Отклики на задание ({$repliesCount})" : 'Мой отклик';
    }

    public static function getTaskReplies(Task $task): array
    {
        if (!empty($task->replies) && $task->customer_id === Yii::$app->user->id) {
            return $task->replies;
        }

        $callback = fn($reply) => $reply->user_id === Yii::$app->user->id;

        return array_filter($task->replies, $callback);
    }
}
