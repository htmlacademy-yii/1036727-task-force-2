<?php

namespace app\services;

use yii\db\Expression;
use app\models\Task;
use app\models\forms\SearchForm;
use app\services\UserService;

class TaskService
{
    const STATUS_NEW_ID = 1;

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
}
