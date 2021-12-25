<?php

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\BaseInflector;
use Anatolev\Utils\DataConverter;
use Anatolev\Exception\SourceFileException;

class ImportController extends Controller
{
    const TABLES = [
        'category',
        'city',
        'user',
        'task_status',
        'task'
    ];

    /**
     * @param array $tables example: user,task
     * @return int Exit code
     */
    public function actionIndex(array $tables = self::TABLES)
    {
        foreach ($tables as $table) {
            $file_path = __DIR__ . '/../data/' . $table . '.csv';
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
}
