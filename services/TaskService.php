<?php

namespace app\services;

use Yii;
use yii\db\Expression;
use app\models\Task;
use app\models\TaskFile;
use app\models\forms\AddTaskForm;
use app\models\forms\SearchForm;

class TaskService
{
    const STATUS_NEW_ID = 1;

    public function create(AddTaskForm $model): int
    {
        $task = new Task;

        $task->attributes = $model->attributes;
        $task->status_id = self::STATUS_NEW_ID;
        $task->customer_id = Yii::$app->user->id;

        $task->save();
        $this->upload($model, $task->id);

        return $task->id;
    }

    public function getFilteredTasks(SearchForm $model): array
    {
        $query = Task::find()
            ->joinWith('category')
            ->where(['status_id' => self::STATUS_NEW_ID])
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

    public function getTaskById(int $id): ?Task
    {
        $query = Task::find()
            ->joinWith('replies.author')
            ->where(['task.id' => $id]);

        return $query->one();
    }

    private function upload(AddTaskForm $model, int $task_id): void
    {
        foreach ($model->files as $file) {
            $file_path = uniqid('file_') . '.' . $file->extension;
            $file->saveAs(Yii::getAlias('@files') . '/' . $file_path);

            $task_file = new TaskFile;
            $task_file->path = $file_path;
            $task_file->task_id = $task_id;

            $task_file->save();
        }
    }
}
