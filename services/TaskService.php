<?php

namespace app\services;

use Yii;
use yii\db\Expression;
use app\models\Task;
use app\models\UserProfile;
use app\models\forms\AddTaskForm;
use app\models\forms\CompleteForm;
use app\models\forms\SearchForm;
use app\services\TaskFileService;
// use app\services\UserProfileService;

class TaskService
{
    public function create(AddTaskForm $model): int
    {
        $task = new Task;

        $task->attributes = $model->attributes;
        $task->status_id = \anatolev\service\Task::STATUS_NEW_ID;
        $task->customer_id = Yii::$app->user->id;

        $task->save();
        $this->upload($model, $task->id);

        return $task->id;
    }

    public function getFilteredTasks(SearchForm $model): array
    {
        $query = Task::find()
            ->joinWith('category')
            ->where(['status_id' => \anatolev\service\Task::STATUS_NEW_ID])
            ->orderBy('task.dt_add DESC');

        if ($model->categories) {
            $query->andWhere(['in', 'task.category_id', $model->categories]);
        }

        if ($model->without_performer) {
            $query->andWhere(['task.executor_id' => null]);
        }

        if (intval($model->period_value) > 0) {
            $exp = new Expression("DATE_SUB(NOW(), INTERVAL {$model->period_value} HOUR)");
            $query->andWhere(['>', 'task.dt_add', $exp]);
        }

        return $query->all();
    }

    public function findOne(int $task_id): ?Task
    {
        return Task::findOne($task_id);
    }

    public function getStatus(int $task_id): ?string
    {
        return Task::findOne($task_id)?->status->inner_name;
    }

    public function isTaskCustomer(int $task_id, int $user_id): bool
    {
        $condition = ['id' => $task_id, 'customer_id' => $user_id];

        return Task::find()->where($condition)->exists();
    }

    public function isTaskExecutor(int $task_id, int $user_id): bool
    {
        $condition = ['id' => $task_id, 'executor_id' => $user_id];

        return Task::find()->where($condition)->exists();
    }

    public function cancel(int $task_id): void
    {
        $task = Task::findOne($task_id);
        $task->status_id = \anatolev\service\Task::STATUS_CANCEL_ID;

        $task->save();
    }

    public function complete(CompleteForm $model): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $task = Task::findOne($model->task_id);
            $task->status_id = $model->task_status;
            $task->save();

            $user = UserProfile::findOne(['user_id' => $task->executor_id]);
            $user->updateCounters(['done_task_count' => 1]);
            $user->save();

            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();
        }
    }
    
    public function refuse(int $task_id): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $task = Task::findOne($task_id);
            $task->status_id = \anatolev\service\Task::STATUS_FAILED_ID;
            $task->save();

            $user = UserProfile::findOne(['user_id' => $task->executor_id]);
            $user->updateCounters(['failed_task_count' => 1]);
            $user->save();

            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();
        }
    }

    private function upload(AddTaskForm $model, int $task_id): void
    {
        foreach ($model->files as $file) {
            $file_path = uniqid("{$file->baseName}_") . '.' . $file->extension;
            $file->saveAs(Yii::getAlias('@files') . '/' . $file_path);

            (new TaskFileService())->create($file_path, $task_id);
        }
    }
}
