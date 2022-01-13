<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\BaseInflector;
use yii\helpers\FileHelper;

use anatolev\utils\DataConverter;
use anatolev\exception\SourceFileException;

class DataController extends Controller
{
    const CSV_MIME_TYPES = ['application/csv', 'text/csv'];

    const TABLES = [
        'city',
        'user',
        'category',
        'user_category',
        'user_profile',
        'task_status',
        'task',
        'review',
        'reply'
    ];

    public $defaultAction = 'import';

    /**
     * Импортирует данные из csv-файлов в бд
     *
     * @param array $tables
     * @return int Exit code
     */
    public function actionImport(array $tables = self::TABLES): int
    {
        foreach ($tables as $table) {
            $file_path = Yii::getAlias('@data') . '/' . $table . '.csv';
            $table = (new BaseInflector())->camelize($table);
            $classname = '\app\models\\' . $table;

            foreach ((new DataConverter($file_path))->convert()->asArray() as $row) {
                $table = new $classname();
                $table->attributes = $row;
                $table->save();
            }
        }

        return ExitCode::OK;
    }

    /**
     * Конвертирует данные из csv-файлов в sql
     *
     * @param string $output_dir
     * @return int Exit code
     */
    public function actionConvert(string $output_dir = 'sql'): int
    {
        foreach (FileHelper::findFiles(Yii::getAlias('@data')) as $file) {
            $file_type = FileHelper::getMimeType($file);

            if (in_array($file_type, self::CSV_MIME_TYPES)) {
                $converter = new DataConverter($file);
                $converter->convert()->dumpToSqlFile($output_dir);
            }
        }

        return ExitCode::OK;
    }
}
