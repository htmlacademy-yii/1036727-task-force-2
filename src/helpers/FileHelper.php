<?php

namespace anatolev\helpers;

use Yii;
use app\models\TaskFile;

class FileHelper
{
    const BYTE_PER_KILOBYTE = 1024;

    public static function getSize(string $file_path): int
    {
        $value = filesize(Yii::getAlias('@files') . '/' . $file_path) / self::BYTE_PER_KILOBYTE;

        return ceil($value);
    }

    public static function getExist(array $files): array
    {
        $callback = function ($file) {
            if ($file instanceof TaskFile && file_exists(Yii::getAlias('@files') . '/' . $file->path)) {
                return true;
            }

            return false;
        };

        return array_filter($files, $callback);
    }
}