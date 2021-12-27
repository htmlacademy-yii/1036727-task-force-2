<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "reply".
 *
 * @property int $id
 * @property string $dt_add
 * @property int|null $price
 * @property string|null $comment
 * @property int $task_id
 * @property int $author_id
 *
 * @property User $author
 * @property Task $task
 */
class Reply extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reply';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['price', 'task_id', 'author_id'], 'integer'],
            [['task_id', 'author_id'], 'required'],
            [['comment'], 'string', 'max' => 255],
            [['author_id'], 'exist', 'targetClass' => User::class, 'targetAttribute' => ['author_id' => 'id']],
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
            'price' => 'Price',
            'comment' => 'Comment',
            'task_id' => 'Task ID',
            'author_id' => 'Author ID',
        ];
    }

    /**
     * Gets query for [[Author]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }

    /**
     * Gets query for [[Task]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::className(), ['id' => 'task_id']);
    }
}
