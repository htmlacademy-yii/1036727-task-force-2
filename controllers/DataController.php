<?php

namespace app\controllers;

use yii\web\Controller;
use yii\helpers\FileHelper;

use anatolev\utils\DataConverter;
use anatolev\exception\SourceFileException;

class DataController extends Controller
{
    const CSV_MIME_TYPES = ['application/csv', 'text/csv'];

    public function actionConvert(string $input_dir = 'data'): void
    {
        $input_dir = '../' . $input_dir;

        if (!file_exists($input_dir)) {
            throw new SourceFileException('Каталог не существует');
        }

        foreach (FileHelper::findFiles($input_dir) as $file) {
            $file_type = FileHelper::getMimeType($file);

            if (in_array($file_type, self::CSV_MIME_TYPES)) {
                $converter = new DataConverter($file);
                $converter->convert()->dumpToSqlFile(output_dir: 'sql');
            }
        }
    }
}
