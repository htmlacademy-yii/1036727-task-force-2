<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "message".
 *
 * @property int $id
 * @property string $dt_add
 * @property string $content
 * @property int $read_status
 * @property int $sender_id
 * @property int $recipient_id
 *
 * @property User $recipient
 * @property User $sender
 */
class Message extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'message';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['content', 'sender_id', 'recipient_id'], 'required'],
            [['read_status', 'sender_id', 'recipient_id'], 'integer'],
            [['content'], 'string', 'max' => 255],
            [['recipient_id'], 'exist', 'targetClass' => User::class, 'targetAttribute' => ['recipient_id' => 'id']],
            [['sender_id'], 'exist', 'targetClass' => User::class, 'targetAttribute' => ['sender_id' => 'id']],
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
            'content' => 'Content',
            'read_status' => 'Read Status',
            'sender_id' => 'Sender ID',
            'recipient_id' => 'Recipient ID',
        ];
    }

     /**
     * Gets query for [[Recipient]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRecipient()
    {
        return $this->hasOne(User::class, ['id' => 'recipient_id']);
    }

    /**
     * Gets query for [[Sender]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSender()
    {
        return $this->hasOne(User::class, ['id' => 'sender_id']);
    }
}
