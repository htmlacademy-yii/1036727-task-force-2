<?php

namespace app\services;

use Yii;
use yii\db\Expression;
use app\models\Task;
use app\models\UserProfile;
use app\models\forms\AddTaskForm;
use app\models\forms\CompleteForm;
use app\models\forms\SearchForm;
use anatolev\helpers\TaskHelper;
use anatolev\service\Task as Task2;

class TaskService
{
    /**
     * @param int $taskId
     * @param int $userId
     * @return bool
     */
    public function canChangeReplyStatus(int $taskId, int $userId): bool
    {
        $isActual = $this->isActual($taskId);
        $taskStatus = $this->getStatus($taskId);
        $isCustomer = $this->isTaskCustomer($taskId, $userId);

        return $isActual && $taskStatus === Task2::STATUS_NEW && $isCustomer;
    }

    /**
     * @param int $taskId
     * @return void
     */
    public function cancel(int $taskId): void
    {
        $task = Task::findOne($taskId);
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
     * @param int $taskId
     * @return ?Task
     */
    public function findOne(int $taskId): ?Task
    {
        return Task::findOne($taskId);
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
     * @param int $taskId
     * @return ?string
     */
    public function getStatus(int $taskId): ?string
    {
        return Task::findOne($taskId)?->status->inner_name;
    }

    /**
     * @param int $userId
     * @param string $filter
     * @return Task[]
     */
    public function getCustomerTasks(int $userId, ?string $filter = null): array
    {
        $query = Task::find()->where(['customer_id' => $userId]);

        switch ($filter) {
            case 'new':
                $query->andWhere(['status_id' => Task2::STATUS_NEW_ID]);
                break;

            case 'progress':
                $query->andWhere(['status_id' => Task2::STATUS_WORK_ID]);
                break;

            case 'closed':
                $ids = [Task2::STATUS_CANCEL_ID, Task2::STATUS_DONE_ID, Task2::STATUS_FAILED_ID];
                $query->andWhere(['in', 'status_id', $ids]);
                break;
        }

        return $query->all();
    }

    /**
     * @param int $userId
     * @param string $filter
     * @return Task[]
     */
    public function getExecutorTasks(int $userId, ?string $filter = null): array
    {
        $query = Task::find()->joinWith('replies r')->where(['r.user_id' => $userId]);

        switch ($filter) {
            case 'progress':
                $query->andWhere(['task.status_id' => Task2::STATUS_WORK_ID]);
                break;

            case 'overdue':
                $query
                    ->andWhere(['task.status_id' => Task2::STATUS_WORK_ID])
                    ->andWhere(['<', 'task.expire', new Expression('CURRENT_DATE()')]);
                break;

            case 'closed':
                $ids = [Task2::STATUS_DONE_ID, Task2::STATUS_FAILED_ID];
                $query->andWhere(['in', 'task.status_id', $ids]);
                break;
        }

        return $query->all();
    }

    /**
     * @param int $replyId
     * @return bool
     */
    public function isActual(int $taskId): bool
    {
        $task = $this->findOne($taskId);

        return $task && TaskHelper::isActual($task);
    }

    /**
     * @param int $taskId
     * @param int $userId
     * @return bool
     */
    public function isTaskCustomer(int $taskId, int $userId): bool
    {
        $condition = ['id' => $taskId, 'customer_id' => $userId];

        return Task::find()->where($condition)->exists();
    }

    /**
     * @param int $taskId
     * @param int $userId
     * @return bool
     */
    public function isTaskExecutor(int $taskId, int $userId): bool
    {
        $condition = ['id' => $taskId, 'executor_id' => $userId];

        return Task::find()->where($condition)->exists();
    }

    /**
     * @param int $taskId
     * @return void
     */
    public function refuse(int $taskId): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $task = Task::findOne($taskId);
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
     * @param int $taskId
     * @return void
     */
    private function upload(AddTaskForm $model, int $taskId): void
    {
        foreach ($model->files as $file) {
            $filePath = uniqid("{$file->baseName}_") . '.' . $file->extension;
            $file->saveAs(Yii::getAlias('@files') . '/' . $filePath);

            (new TaskFileService())->create($filePath, $taskId);
        }
    }
}
