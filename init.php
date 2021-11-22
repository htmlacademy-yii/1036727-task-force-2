<?php

require_once('functions.php');
require_once('define.php');

set_error_handler('errorHandler');
set_exception_handler('exceptionHandler');
ini_set('assert.callback', 'assertHandler');
