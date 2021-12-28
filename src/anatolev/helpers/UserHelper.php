<?php

namespace anatolev\helpers;

use yii\helpers\Url;
use app\models\User;

class UserHelper
{
    public static function getAvatar(User $user): string
    {
        $avatar_path = $user->profile->avatar_path ?? '';

        return Url::to(["@web/uploads/avatars/{$avatar_path}"]);
    }
}
