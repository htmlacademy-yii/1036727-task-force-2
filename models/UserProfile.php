<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_profile".
 *
 * @property int $id
 * @property string|null $address
 * @property string|null $birthday
 * @property string|null $about
 * @property string|null $avatar_path
 * @property string|null $phone
 * @property string|null $skype
 * @property string|null $messenger
 * @property int $new_message
 * @property int $activities
 * @property int $new_review
 * @property int $show_contacts
 * @property int $show_profile
 * @property int $failed_task_count
 * @property int $user_id
 *
 * @property PhotoOfWork[] $photoOfWorks
 * @property User $user
 */
class UserProfile extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_profile';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['birthday'], 'safe'],
            [['new_message', 'activities', 'new_review', 'show_contacts', 'show_profile', 'failed_task_count', 'user_id'], 'integer'],
            [['user_id'], 'required'],
            [['address', 'about', 'avatar_path', 'skype'], 'string', 'max' => 128],
            [['phone'], 'string', 'max' => 11],
            [['messenger'], 'string', 'max' => 64],
            [['avatar_path'], 'unique'],
            [['phone'], 'unique'],
            [['skype'], 'unique'],
            [['messenger'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'address' => 'Address',
            'birthday' => 'Birthday',
            'about' => 'About',
            'avatar_path' => 'Avatar Path',
            'phone' => 'Phone',
            'skype' => 'Skype',
            'messenger' => 'Messenger',
            'new_message' => 'New Message',
            'activities' => 'Activities',
            'new_review' => 'New Review',
            'show_contacts' => 'Show Contacts',
            'show_profile' => 'Show Profile',
            'failed_task_count' => 'Failed Task Count',
            'user_id' => 'User ID',
        ];
    }

    /**
     * Gets query for [[PhotoOfWorks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPhotoOfWorks()
    {
        return $this->hasMany(PhotoOfWork::className(), ['profile_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
