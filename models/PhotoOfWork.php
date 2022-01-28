<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "photo_of_work".
 *
 * @property int $id
 * @property string $path
 * @property int $profile_id
 *
 * @property UserProfile $profile
 */
class PhotoOfWork extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{photo_of_work}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['path', 'profile_id'], 'required'],
            [['profile_id'], 'integer'],
            [['path'], 'string', 'max' => 128],
            [['path'], 'unique'],
            [['profile_id'], 'exist', 'targetClass' => UserProfile::class, 'targetAttribute' => ['profile_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'path' => 'Path',
            'profile_id' => 'Profile ID',
        ];
    }

     /**
     * Gets query for [[Profile]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(UserProfile::class, ['id' => 'profile_id']);
    }
}
