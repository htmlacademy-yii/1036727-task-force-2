<?php

namespace app\controllers;

use yii\web\Controller;
use Anatolev\Utils\DataConverter;
use Anatolev\Exception\SourceFileException;

class DataController extends Controller
{
    public function actionConvert()
    {
        $input_dir = 'data';

        if (!file_exists($input_dir)) {
            throw new SourceFileException('Каталог не существует');
        }

        $files = array_diff(scandir($input_dir), ['.', '..']);

        foreach ($files as $file) {
            $file_path = "{$input_dir}/{$file}";
            $file_type = mime_content_type($file_path);

            if (in_array($file_type, ['application/csv', 'text/csv'])) {
                $converter = new DataConverter($file_path);
                $converter->convert()->dumpToSqlFile(output_dir: 'sql');
            }
        }
    }
}
