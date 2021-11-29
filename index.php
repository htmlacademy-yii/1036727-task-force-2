<?php
declare(strict_types=1);

use Anatolev\Service\Task;
use Anatolev\Service\{ActCancel, ActDone, ActRefuse, ActRespond};

use Anatolev\Exception\SourceFileException;
use Anatolev\Exception\StatusNotExistException;
use Anatolev\Exception\ActionNotExistException;

require_once 'vendor/autoload.php';
require_once 'init.php';

$task = new Task(1, 2);

try {
    assert($task->getStatusMap() === TASK_STATUS_MAP);
    assert($task->getActionMap() === TASK_ACTION_MAP);

    assert($task->getNextStatus('act_cancel') === Task::STATUS_CANCEL);
    assert($task->getNextStatus('act_respond') === Task::STATUS_WORK);
    assert($task->getNextStatus('act_done') === Task::STATUS_DONE);
    assert($task->getNextStatus('act_refuse') === Task::STATUS_FAILED);

    assert($task->getAvailableActions('new', 1)[0] instanceof ActRespond);
    assert($task->getAvailableActions('new', 2)[0] instanceof ActCancel);
    assert($task->getAvailableActions('new', 3) === []);

    assert($task->getAvailableActions('work', 1)[0] instanceof ActRefuse);
    assert($task->getAvailableActions('work', 2)[0] instanceof ActDone);
    assert($task->getAvailableActions('work', 3) === []);
} catch (ErrorException $ex) {
    error_log($ex->__toString() . "\n");
} catch (SourceFileException $ex) {
    error_log($ex->__toString() . "\n");
} catch (StatusNotExistException $ex) {
    error_log($ex->__toString() . "\n");
} catch (ActionNotExistException $ex) {
    error_log($ex->__toString() . "\n");
}
