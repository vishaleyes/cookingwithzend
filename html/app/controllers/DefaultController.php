<?php

/**
 * Default Controller - An Abstract class that extends Zend_Controller_Action and should be used as a parent class for all other controllers
 */

abstract class DefaultController extends Zend_Controller_Action {
	
	/**
	* Application's Version number (for example 1.0.0)
	*
	* @var string
	*/
	protected $release_no;
	/**
	* Environment (for example Production/Staging/Development)
	*
	* @var string
	*/
	protected $environment;
	/**
	* Config object - based on config.xml file
	*
	* @var Zend_Config
	*/
	protected $config;
	/**
	* Database object (for example Zend_Db_Adapter_Pdo_Mysql object)
	*
	* @var object
	*/
	protected $db;
	/**
	* Logger Object (usage : $this->log->log("Message to log", 0);)
	*
	* @var Zend_Log
	*/
	protected $log;
	/**
  * Session object (usage : $this->session->session_key = $session_value)
	*
	* @var Zend_Session
	*/
	protected $session;
	/**
	* GET/POST Request array
	*
	* @var array
	*/
	protected $request;
	/**
	* Cache Object
	*
	* @var Zend_Cache
	*/
	protected $cache;
	/**
	 * Folder where all pages should be stored
	 *
	 * @var string
	 */
	protected $pagesFolder;
	/**
	 * Folder where all temlpates should be stored
	 *
	 * @var string
	 */
	protected $templatesFolder;
	/**
	 * Folder where all other application's specific include files should be stored
	 *
	 * @var string
	 */
	protected $includesFolder;
	
	public function init()
	{
		$this->_log = Zend_Registry::get('log');
		$this->_db = Zend_Registry::get('db');

		// Get the session for all controllers
		$this->_session = Zend_Registry::get('session');
		$this->view->session = $this->_session;
		
		// Setup the redirector
		$this->_redirector = $this->_helper->getHelper('Redirector');

		// Add the messenger
		$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
		$this->view->message = $this->_flashMessenger;
		
	}
}
