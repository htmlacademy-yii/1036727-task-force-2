<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;
use anatolev\service\Task;

class CompleteForm extends Model
{
    public $task_status;
    public $comment;
    public $rating;
    public $task_id;

    public function rules()
    {
        return [
            [['task_status', 'comment', 'rating', 'task_id'], 'required'],
            [['task_status'], 'in', 'range' => [Task::STATUS_DONE_ID, Task::STATUS_FAILED_ID]],
            [['comment'], 'string', 'max' => 255],
            [['rating'], 'integer', 'min' => 1, 'max' => Yii::$app->params['maxUserRating']],
            [['task_id'], 'integer'],
            [['task_id'], 'exist', 'targetClass' => \app\models\Task::class, 'targetAttribute' => 'id'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'task_status' => 'Задание выполнено?',
            'comment' => 'Комментарий',
            'rating' => 'Оценка',
        ];
    }
}
