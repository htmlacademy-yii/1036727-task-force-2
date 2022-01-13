<?php

namespace anatolev\helpers;

use Yii;
use yii\helpers\Url;
use app\models\User;

class UserHelper
{
    const AVATARS_UPLOAD_DIR = 'uploads/avatars/';

    public static function getAvatar(User $user): string
    {
        $avatar_path = self::AVATARS_UPLOAD_DIR . $user->profile->avatar_path ?? '';

        return file_exists($avatar_path) ? Url::to([$avatar_path]) : '';
    }
}
