<?php

namespace anatolev\helpers;

use Yii;
use yii\helpers\Url;
use app\models\User;

class UserHelper
{
    const DEFAULT_AVATAR_COUNT = 5;

    public static function getAvatar(User $user): string
    {
        $defaultAvatarPath = '../../img/avatars/' . rand(1, self::DEFAULT_AVATAR_COUNT) . '.png';

        if (isset($user->profile->avatar_path)) {
            $avatar_path = Yii::getAlias('@avatars') . '/' . $user->profile->avatar_path;

            return file_exists($avatar_path) ? Url::to([$avatar_path]) : $defaultAvatarPath;
        }

        return $defaultAvatarPath;
    }
}
