<?php

namespace app\services;

use app\models\Reply;
use app\models\forms\ResponseForm;
use app\services\TaskService;
use anatolev\service\Task;

class ReplyService
{
    /**
     * @param ResponseForm $model
     * @return int
     */
    public function create(ResponseForm $model): int
    {
        $reply = new Reply();
        $reply->attributes = $model->attributes;
        $reply->save();

        return $model->task_id;
    }

    /**
     * @param int $reply_id
     * @return int
     */
    public function accept(int $reply_id): int
    {
        $reply = Reply::findOne($reply_id);
        $task = (new TaskService())->findOne($reply->task_id);

        $task->executor_id = $reply->user_id;
        $task->status_id = Task::STATUS_WORK_ID;
        $task->save();

        return $reply->task_id;
    }

    /**
     * @param $reply_id
     * @return int
     */
    public function refuse(int $reply_id): int
    {
        $reply = Reply::findOne($reply_id);
        $reply->denied = 1;
        $reply->save();

        return $reply->task_id;
    }

    /**
     * @param int $reply_id
     * @return ?Reply
     */
    public function findOne(int $reply_id): ?Reply
    {
        return Reply::findOne($reply_id);
    }

    /**
     * @param int $task_id
     * @param int $user_id
     * @return bool
     */
    public function exist(int $task_id, int $user_id): bool
    {
        $condition = ['task_id' => $task_id, 'user_id' => $user_id];

        return Reply::find()->where($condition)->exists();
    }
}
