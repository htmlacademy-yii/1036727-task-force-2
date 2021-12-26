<?php

namespace app\controllers;

use yii\web\Controller;
use yii\helpers\FileHelper;
use Anatolev\Utils\DataConverter;
use Anatolev\Exception\SourceFileException;

class DataController extends Controller
{
    public function actionConvert()
    {
        $input_dir = '../data';

        if (!file_exists($input_dir)) {
            throw new SourceFileException('Каталог не существует');
        }

        foreach (FileHelper::findFiles($input_dir) as $file) {
            $file_type = FileHelper::getMimeType($file);

            if (in_array($file_type, ['application/csv', 'text/csv'])) {
                $converter = new DataConverter($file);
                $converter->convert()->dumpToSqlFile(output_dir: 'sql');
            }
        }
    }
}
