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
 * @property string|null $contact_phone
 * @property string|null $contact_skype
 * @property string|null $contact_tg
 * @property bool $notice_message
 * @property bool $notice_actions
 * @property bool $notice_review
 * @property bool $show_contacts
 * @property bool $show_profile
 * @property float $current_rate
 * @property int $done_task_count
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
            [['notice_message', 'notice_actions', 'notice_review', 'show_contacts', 'show_profile', 'done_task_count', 'failed_task_count', 'user_id'], 'integer'],
            [['user_id'], 'required'],
            [['address', 'about', 'avatar_path', 'contact_skype'], 'string', 'max' => 128],
            [['contact_phone'], 'string', 'max' => 11],
            [['contact_tg'], 'string', 'max' => 64],
            [['avatar_path'], 'unique'],
            [['contact_phone'], 'unique'],
            [['contact_skype'], 'unique'],
            [['contact_tg'], 'unique'],
            [['user_id'], 'exist', 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
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

            'contact_phone' => 'Contact Phone',
            'contact_skype' => 'Contact Skype',
            'contact_tg' => 'Contact Tg',

            'notice_message' => 'Notice Message',
            'notice_actions' => 'Notice Actions',
            'notice_review' => 'Notice Review',

            'show_contacts' => 'Show Contacts',
            'show_profile' => 'Show Profile',
            'current_rate' => 'Current Rate',
            'done_task_count' => 'Done Task Count',
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
        return $this->hasMany(PhotoOfWork::class, ['profile_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
