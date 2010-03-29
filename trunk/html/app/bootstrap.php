<?php

defined('APPLICATION_PATH')
    or define('APPLICATION_PATH', dirname(__FILE__));

defined('APPLICATION_ENVIRONMENT')
    or define('APPLICATION_ENVIRONMENT', 'development');

set_include_path( 
	APPLICATION_PATH . '/../lib' . PATH_SEPARATOR . 
	APPLICATION_PATH . '/controllers' . PATH_SEPARATOR .
	APPLICATION_PATH . PATH_SEPARATOR .
	get_include_path()
);

require_once 'Initializer.php';

// Prepare the front controller. 
require_once 'Zend/Controller/Front.php';
$frontController = Zend_Controller_Front::getInstance(); 

// Change to 'production' parameter under production environemtn
$frontController->registerPlugin(new Initializer( APPLICATION_ENVIRONMENT ));

// Dispatch the request using the front controller. 
$frontController->dispatch(); 
?>