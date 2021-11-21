<?php
declare(strict_types=1);

use Anatolev\Service\Task;
use Anatolev\Service\{ActCancel, ActDone, ActRefuse, ActRespond};

use Anatolev\Exception\BaseException;
use Anatolev\Exception\ClassNotFoundException;
use Anatolev\Exception\StatusNotExistException;
use Anatolev\Exception\ActionNotExistException;

require_once('vendor/autoload.php');
require_once('define.php');

set_exception_handler(BaseException::class . '::exceptionHandler');
set_error_handler(BaseException::class . '::errorHandler');

$task = new Task(1, 2);

try {
    assert($task->getStatusMap() === TASK_STATUS_MAP);
    assert($task->getActionMap() === TASK_ACTION_MAP);

    assert($task->getNextStatus('act_cancel') === 'cancel');
    assert($task->getNextStatus('act_respond') === 'work');
    assert($task->getNextStatus('act_done') === 'done');
    assert($task->getNextStatus('act_refuse') === 'failed');

    assert($task->getAvailableActions('new', 1)[0] instanceof ActRespond);
    assert($task->getAvailableActions('new', 2)[0] instanceof ActCancel);
    assert($task->getAvailableActions('new', 3) === []);

    assert($task->getAvailableActions('work', 1)[0] instanceof ActRefuse);
    assert($task->getAvailableActions('work', 2)[0] instanceof ActDone);
    assert($task->getAvailableActions('work', 3) === []);
} catch (ClassNotFoundException $ex) {
    error_log($ex->__toString() . "\n");
} catch (StatusNotExistException $ex) {
    error_log($ex->__toString() . "\n");
} catch (ActionNotExistException $ex) {
    error_log($ex->__toString() . "\n");
} catch (ErrorException $ex) {
    error_log($ex->__toString() . "\n");
}
