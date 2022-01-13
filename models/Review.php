<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "review".
 *
 * @property int $id
 * @property string $dt_add
 * @property int $rate
 * @property string $comment
 * @property int $task_id
 *
 * @property Task $task
 */
class Review extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'review';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rate', 'comment', 'task_id'], 'required'],
            [['rate', 'task_id'], 'integer'],
            [['comment'], 'string', 'max' => 255],
            [['task_id'], 'exist', 'targetClass' => Task::class, 'targetAttribute' => ['task_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dt_add' => 'Dt Add',
            'rate' => 'Rate',
            'comment' => 'Comment',
            'task_id' => 'Task ID',
        ];
    }

    /**
     * Gets query for [[Task]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::class, ['id' => 'task_id']);
    }
}
