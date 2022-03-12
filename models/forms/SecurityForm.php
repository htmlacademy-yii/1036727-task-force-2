<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\Category;
use app\models\User;
use app\services\UserService;

class SecurityForm extends Model
{
    public $private_contacts;
    public $old_password;
    public $new_password;
    public $new_password_repeat;

    public function rules()
    {
        return [
            [['old_password'], 'required'],
            [['private_contacts'], 'boolean'],
            [['old_password'], 'string', 'length' => [6, 255]],
            [['old_password'], 'validatePassword'],
            [['new_password'], 'required',
                'when' => function ($model) {
                    return !$model->hasErrors('old_password');
                },
                'whenClient' => "function (attribute, value) {
                    return !$('#securityform-old_password').attr('aria-invalid');
                }"
            ],
            [['new_password'], 'string', 'length' => [6, 255],
                'whenClient' => "function (attribute, value) {
                    return !$('#securityform-old_password').attr('aria-invalid');
                }"
            ],
            [['new_password_repeat'], 'required',
                'whenClient' => "function (attribute, value) {
                    return !$('#securityform-new_password').attr('aria-invalid');
                }"
            ],
            [['new_password_repeat'], 'compare', 'compareAttribute' => 'new_password',
                'whenClient' => "function (attribute, value) {
                    return !$('#securityform-new_password').attr('aria-invalid');
                }"
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'private_contacts' => 'Показывать мои контакты только заказчику',
            'old_password' => 'Старый пароль',
            'new_password' => 'Новый пароль',
            'new_password_repeat' => 'Повтор нового пароля'
        ];
    }

    public function validatePassword($attribute, $params): void
    {
        if (!$this->hasErrors()) {
            $email = (new UserService())->findOne(Yii::$app->user->id)->email;
            $user = (new UserService())->getUser($email);
            if (!$user || !$user->validatePassword($this->old_password)) {
                $this->addError($attribute, 'Неправильный пароль');
            }
        }
    }

    /**
     * @param User $user
     * @return void
     */
    public function loadCurrentValues(User $user): void
    {
        $this->private_contacts = $user->profile->private_contacts;
    }
}
