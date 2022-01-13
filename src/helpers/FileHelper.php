<?php

namespace anatolev\helpers;

use app\models\TaskFile;

class FileHelper
{
    const BYTE_PER_KILOBYTE = 1024;
    const UPLOAD_DIR = 'uploads/files/';

    public static function getSize(string $file_path): int
    {
        $value = filesize(self::UPLOAD_DIR . $file_path) / self::BYTE_PER_KILOBYTE;

        return ceil($value);
    }

    public static function getExist(array $files): array
    {
        $callback = function ($file) {
            if ($file instanceof TaskFile && file_exists(self::UPLOAD_DIR . $file->path)) {
                return true;
            }

            return false;
        };

        return array_filter($files, $callback);
    }
}