<?php

/**
 * Database file - registers database object
 * @author Marcin Dominas : marcin.dominas@bd-ntwk.com
 * @package Generic Framework
 * @see : http://digitalwiki.bd-ntwk.com/index.php?title=BD_Network_PHP_Application_Framework
 */

$use_db = true;

try { 

	$db = Zend_Db::factory('Pdo_Mysql', array(
			    'host'     => $config->database->host,
			    'username' => $config->database->username,
			    'password' => $config->database->password,
			    'dbname'   => $config->database->name));
			    
	Zend_Db_Table_Abstract::setDefaultAdapter($db);
			    
} catch (Exception $e) {
	// database object not available
	$use_db = false;
	
}


if ($use_db) {

	try {
		
		$test = $db->fetchOne('SELECT 1=1');
		
		// turn on database profiler
		$db->getProfiler()->setEnabled(true);
		
	} catch ( Exception $e ) {
		$db = null;
		$use_db = false;
	}
} else {
	$db = null;
	$use_db = false;
}

Zend_Registry::set('db', $db);
