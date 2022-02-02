<?php

namespace app\services;

use app\models\TaskFile;

class TaskFileService
{
    public function create(string $file_path, int $task_id): void
    {
        $task_file = new TaskFile();
        $task_file->path = $file_path;
        $task_file->task_id = $task_id;

        $task_file->save();
    }
}
