<?php
error_reporting(E_ALL | E_STRICT);

define('BASE_PATH', realpath(dirname(__FILE__) . '/../../'));
define('APPLICATION_PATH', BASE_PATH . '/application');

// Define application environment
define('APPLICATION_ENV', 'testing');

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
	'.',
    BASE_PATH . '/library',
    get_include_path(),
)));

//var_dump(get_include_path());

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
require_once 'ControllerTestCase.php';