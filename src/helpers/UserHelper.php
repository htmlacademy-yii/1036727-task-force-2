<?php

namespace anatolev\helpers;

use Yii;
use yii\helpers\Url;
use app\models\User;

class UserHelper
{
    public static function getAvatar(User $user): string
    {
        $avatar_path = Yii::getAlias('@avatars') . '/' . $user->profile->avatar_path ?? '';

        return file_exists($avatar_path) ? Url::to([$avatar_path]) : '';
    }
}
