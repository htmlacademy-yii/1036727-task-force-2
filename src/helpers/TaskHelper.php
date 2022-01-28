<?php

namespace anatolev\helpers;

use Yii;
use app\models\Task;

class TaskHelper
{
    public static function getTaskReplies(Task $task): array
    {
        if (!empty($task->replies) && $task->customer_id === Yii::$app->user->id) {
            return $task->replies;
        }

        return array_filter($task->replies, function ($reply) {
            return $reply->author_id === Yii::$app->user->id;
        });
    }
}
