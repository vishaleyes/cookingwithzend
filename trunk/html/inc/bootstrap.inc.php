<?php

/**
 * Bootstrap file
 */

// Return current unix timestamp with microseconds
function getmicrotime()
{ 
	list($usec, $sec) = explode(" ",microtime()); 
	return ((float)$usec + (float)$sec); 
}
define('TIMESTART',getmicrotime());

/**
 * Error reporting
 */
//error_reporting(E_ALL ^ E_ALL );
error_reporting( E_ALL & ~E_NOTICE );


/**
 * Some useful constants
 *
 */
define('DS', DIRECTORY_SEPARATOR);
define('PS', PATH_SEPARATOR);
define('BP', dirname(dirname(__FILE__)));
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']);
/**
 * Include path
 */
ini_set('include_path', ini_get('include_path').PS.BP.'/lib'.PS.BP.'/app/controllers'.PS.BP.'/app/models');

/**
 * Memory Limit
 */
ini_set("memory_limit","64M"); 

/**
 * Max Execution Time
 */
ini_set("max_execution_time","60"); 

/**
 * Make sure we use autoload to give the application the last chance to include the files required before fatal error is thrown
 */
require_once('lib/Zend/Loader.php');

function __autoload($class) {
    // don't auto load controllers (except the default controller)! we need to catch these with the Zend_Controller_Plugin_ErrorHandler
	try {
			if ((strpos($class,'Controller') === false) || 
	
	    	(strpos($class,'DefaultController') !== false) || 
	    	(strpos($class,'Zend_Controller') !== false))
	    {
			Zend_Loader::loadClass($class);
	    }
	} catch(Exception $e) {
		
	}
}

/**
 * Get current version's release
 */

$url = '$URL$'; // svn replaces this with the full path
@preg_split( ':release-(.*)\\/:', $url, $match );
if ( @sizeof( $match ) > 0 ) {
        $release_no = $match[1];
} else {
        $release_no = 'Development';
}
Zend_Registry::set('release_no', $release_no);
Zend_Registry::set('request', $_REQUEST);

/**
 * Set up the front controller and error plugin
 */
$controller = Zend_Controller_Front::getInstance(); 
$controller->setControllerDirectory('app/controllers');

$router = $controller->getRouter();

// Might move these into the config or a seperate Route include
$route = new Zend_Controller_Router_Route(
	':controller/:action/*'
);
$router->addRoute('default', $route);

$route = new Zend_Controller_Router_Route(
	'tag/:name/*3', 
	array( 
		'controller' => 'tag',
		'action'     => 'index'
	)
);
$router->addRoute('tag', $route);



