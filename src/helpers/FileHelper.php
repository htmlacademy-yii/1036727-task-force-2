<?php

namespace anatolev\helpers;

use Yii;
use app\models\TaskFile;

class FileHelper
{
    const BYTE_PER_KILOBYTE = 1024;

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

    public static function getName(string $filePath): string
    {
        return (explode('_', $filePath)[0] ?? '') . self::getExtension($filePath);
    }

    public static function getSize(string $filePath): int
    {
        $value = filesize(Yii::getAlias('@files') . '/' . $filePath) / self::BYTE_PER_KILOBYTE;

        return ceil($value);
    }

    private static function getExtension(string $filePath): string
    {
        $filePathParts = explode('.', $filePath);

        return '.' . array_pop($filePathParts);
    }
}