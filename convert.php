<?php
declare(strict_types=1);

use Anatolev\Utils\DataConverter;
use Anatolev\Exception\SourceFileException;

require_once 'vendor/autoload.php';
require_once 'init.php';

try {

    (new DataConverter('data'))->convert();

} catch (\ErrorException $ex) {
    error_log($ex->__toString() . "\n");
} catch (SourceFileException $ex) {
    error_log($ex->__toString() . "\n");
}
