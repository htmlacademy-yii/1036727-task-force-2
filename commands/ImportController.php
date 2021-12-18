<?php

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\BaseInflector;

use Anatolev\Utils\DataConverter;
use Anatolev\Exception\SourceFileException;

class ImportController extends Controller
{
    public function actionIndex(string $tables = 'category, city, task_status')
    {
        foreach (explode(', ', $tables) as $table) {
            $file_path = __DIR__ . '/../web/data/' . $table . '.csv';
            $classname = '\app\models\\' . ucfirst(str_replace('_', '', $table));
            $classname = (new BaseInflector())->camelize($classname);

            foreach ((new DataConverter($file_path))->convert() as $row) {
                $table = new $classname();
                $table->attributes = $row;
                $table->save();
            }
        }

        return ExitCode::OK;
    }
}
