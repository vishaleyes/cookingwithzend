<?php

/**
 * Session file - Sets Up Session Handling so if "sessions" table exists and database is supported then session is stored in Database otherwise it's stored in the filesystem
 * @author Marcin Dominas : marcin.dominas@bd-ntwk.com
 * @package Generic Framework
 * @see : http://digitalwiki.bd-ntwk.com/index.php?title=BD_Network_PHP_Application_Framework
 */

require_once('ZendExtended/Zend_Session_SaveHandler_Db.php');

// Checking if Sessions can be handled by the database

try {
	
	if (!is_object($db))
	  throw new Exception("Database is not available...",0);
	  
	/* since we cannot serialize/unserialize PDO statements we're caching results array instead... */

	$identifier = "CheckSessionsTable";
	
	if(!$result = $cache->load($identifier)) {
		$result = $db->fetchAll("select 1 and 1 from sessions limit 0,1");
		$cache->save($result, $identifier);
	}

	Zend_Session::setSaveHandler(new Zend_Session_SaveHandler_Db($db));
	
} catch (Exception $e) {
	// An error occured while either trying to access the database table or when trying to set up the Session_SaveHandler
	// Sessions will be stored in the filesystem instead
	$log->log("Couldn't use database session handling... Using filesystem instead...".$e->getMessage(),0);
}

Zend_Session::start($config->session);
$session = new Zend_Session_Namespace();

$signature = sha1("m4xw3ll^S1lver:{$_SERVER['REMOTE_ADDR']}");

if (!isset($session->signature)) {
	$session->signature = $signature;
} elseif ($session->signature != $signature) {
	Zend_Session::destroy(true);
}

Zend_Registry::set('session', $session);