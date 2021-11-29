<?php
declare(strict_types=1);

use Anatolev\Utils\DataConverter;
use Anatolev\Exception\SourceFileException;

require_once 'vendor/autoload.php';
require_once 'init.php';

try {
    $input_dir = 'data';

    if (!file_exists($input_dir)) {
        throw new SourceFileException('Каталог не существует');
    }

    $files = array_diff(scandir($input_dir), ['.', '..']);

    foreach ($files as $file) {
        $file_path = "{$input_dir}/{$file}";
        $file_type = mime_content_type($file_path);

        if (in_array($file_type, ['application/csv', 'text/csv'])) {
            (new DataConverter($file_path))->convert();
        }
    }
} catch (ErrorException $ex) {
    error_log($ex->__toString() . "\n");
} catch (SourceFileException $ex) {
    error_log($ex->__toString() . "\n");
}
