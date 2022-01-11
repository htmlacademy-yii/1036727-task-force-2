<?php

namespace app\models\forms;

use yii\base\Model;
use app\models\City;
use app\models\User;

class SignupForm extends Model
{
    public $name;
    public $email;
    public $city_id;
    public $password;
    public $password_repeat;
    public $is_executor;

    public function rules()
    {
        return [
            [['name', 'email', 'password', 'password_repeat'], 'trim'],
            [['name', 'email', 'city_id', 'password', 'password_repeat'], 'required'],
            [['email'], 'string', 'max' => 128],
            [['email'], 'email'],
            [['email'], 'unique', 'targetClass' => User::class],
            [['name'], 'string', 'length' => [2, 128]],
            [['password'], 'string', 'length' => [6, 255]],
            [['password_repeat'], 'compare', 'compareAttribute' => 'password'],
            [['city_id'], 'integer'],
            [['city_id'], 'exist', 'targetClass' => City::class, 'targetAttribute' => 'id'],
            [['is_executor'], 'boolean']
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Ваше имя',
            'email' => 'Email',
            'city_id' => 'Город',
            'password' => 'Пароль',
            'password_repeat' => 'Повтор пароля',
            'is_executor' => 'я собираюсь откликаться на заказы'
        ];
    }
}
