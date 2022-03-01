<?php

namespace app\services;

use Yii;
use yii\db\Expression;
use app\models\Task;
use app\models\UserProfile;
use app\models\forms\AddTaskForm;
use app\models\forms\CompleteForm;
use app\models\forms\SearchForm;
use app\services\ReplyService;
use app\services\ReviewService;
use app\services\TaskFileService;
use anatolev\helpers\TaskHelper;
use anatolev\service\Task as Task2;

class TaskService
{
    /**
     * @param int $task_id
     * @return void
     */
    public function cancel(int $task_id): void
    {
        $task = Task::findOne($task_id);
        $task->status_id = Task2::STATUS_CANCEL_ID;

        $task->save();
    }

    /**
     * @param AddTaskForm $model
     * @return int
     */
    public function create(AddTaskForm $model): int
    {
        $task = new Task;

        $task->attributes = $model->attributes;
        $task->status_id = Task2::STATUS_NEW_ID;
        $task->customer_id = Yii::$app->user->id;

        $coords = [$task->latitude, $task->longitude];
        $task->city_id = (new CityService())->findByCoords(...$coords)?->id;

        $task->save();
        $this->upload($model, $task->id);

        return $task->id;
    }

    /**
     * @param CompleteForm $model
     * @return void
     */
    public function complete(CompleteForm $model): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $task = Task::findOne($model->task_id);
            $task->status_id = $model->task_status;
            $task->save();
            
            (new UserService())->updateTaskCounter($task->executor_id, $task->status_id);
            (new ReviewService())->create($model);

            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();
        }
    }

    /**
     * @param int $task_id
     * @return ?Task
     */
    public function findOne(int $task_id): ?Task
    {
        return Task::findOne($task_id);
    }

    /**
     * @param SearchForm $model
     * @return Task[]
     */
    public function getFilteredTasks(SearchForm $model): array
    {
        $query = Task::find()
            ->joinWith('category')
            ->where(['status_id' => Task2::STATUS_NEW_ID])
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

    /**
     * @param int $task_id
     * @return ?string
     */
    public function getStatus(int $task_id): ?string
    {
        return Task::findOne($task_id)?->status->inner_name;
    }

    /**
     * @param int $reply_id
     * @return bool
     */
    public function isActual(int $reply_id): bool
    {
        $task = (new ReplyService())->findOne($reply_id)?->task;

        return $task && TaskHelper::isActual($task);
    }

    /**
     * @param int $task_id
     * @param int $user_id
     * @return bool
     */
    public function isTaskCustomer(int $task_id, int $user_id): bool
    {
        $condition = ['id' => $task_id, 'customer_id' => $user_id];

        return Task::find()->where($condition)->exists();
    }

    /**
     * @param int $task_id
     * @param int $user_id
     * @return bool
     */
    public function isTaskExecutor(int $task_id, int $user_id): bool
    {
        $condition = ['id' => $task_id, 'executor_id' => $user_id];

        return Task::find()->where($condition)->exists();
    }

    /**
     * @param int $task_id
     * @return void
     */
    public function refuse(int $task_id): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $task = Task::findOne($task_id);
            $task->status_id = Task2::STATUS_FAILED_ID;
            $task->save();

            $user = UserProfile::findOne(['user_id' => $task->executor_id]);
            $user->updateCounters(['failed_task_count' => 1]);
            $user->save();

            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();
        }
    }

    /**
     * @param AddTaskForm $model
     * @param int $task_id
     * @return void
     */
    private function upload(AddTaskForm $model, int $task_id): void
    {
        foreach ($model->files as $file) {
            $file_path = uniqid("{$file->baseName}_") . '.' . $file->extension;
            $file->saveAs(Yii::getAlias('@files') . '/' . $file_path);

            (new TaskFileService())->create($file_path, $task_id);
        }
    }
}
