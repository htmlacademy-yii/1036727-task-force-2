<?php

namespace app\services;

use app\models\Task;
use app\models\forms\SearchForm;
use yii\db\Expression;

class TaskService
{
    public function getAllTasks(): array
    {
        return Task::find()->all();
    }

    public function getFilteredTasks(SearchForm $model): array
    {
        $query = Task::find()
            ->joinWith('category')
            ->where(['status_id' => 1])
            ->orderBy('dt_add DESC');

        if ($model->categories) {
            $query->andWhere(['in', 'category_id', $model->categories]);
        }

        if ($model->without_performer) {
            $query->andWhere(['executor_id' => null]);
        }

        settype($model->period, 'integer');
        if ($model->period > 0) {
            $exp = new Expression("DATE_SUB(NOW(), INTERVAL {$model->period} HOUR)");
            $query->andWhere(['>', 'dt_add', $exp]);
        }

        return $query->all();
    }
}