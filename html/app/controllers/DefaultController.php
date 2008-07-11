<?php

/**
 * Default Controller - An Abstract class that extends Zend_Controller_Action and should be used as a parent class for all other controllers
 * @author Marcin Dominas : marcin.dominas@bd-ntwk.com
 * @package Generic Framework
 * @see : http://digitalwiki.bd-ntwk.com/index.php?title=BD_Network_PHP_Application_Framework 
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
	
	/**
	 * Method called after __construct() is called, used to initialize some properties that should be common for all controllers extending this class
	 *
	 */
	
	public function init() {
		
		// Setting up application's properties
		
		$this->release_no = Zend_Registry::get('release_no');
		$this->environment = Zend_Registry::get('environment');
		$this->config = Zend_Registry::get('config');
		$this->db = Zend_Registry::get('db');
		$this->log = Zend_Registry::get('log');
		$this->session = Zend_Registry::get('session');
		$this->request = Zend_Registry::get('request');
		$this->cache = Zend_Registry::get('cache');
		
		$this->auth = new Zend_Auth_Adapter_DbTable( $this->db );
		$this->auth->setTableName( $this->config->authentication->tableName )
		           ->setIdentityColumn( $this->config->authentication->identityColumn )
		           ->setCredentialColumn( $this->config->authentication->credentialColumn )
		           ->setCredentialTreatment( $this->config->authentication->credentialTreatment );
		
		$this->pagesFolder = '/pages';
		$this->templatesFolder = '/templates';
		$this->includesFolder = '/includes';
		
		// Setting up view's properties
		$this->view->setScriptPath('app/views');
		$this->view->release_no = $this->release_no;
		$this->view->version = $this->environment;
		$this->view->session = $this->session;
		
		$this->view->pagesFolder = $this->pagesFolder;
		$this->view->templatesFolder = $this->templatesFolder;
		$this->view->includesFolder = $this->includesFolder;

		// Setting up some other properties
		$this->getRecipe();
	}

	protected function loggedIn( $exclusions = array() )
	{
		// figure out whats being requested
		$action = $this->_request->getActionName();

		if ( ! in_array( $action, $exclusions ) )
		{
			// Were not ecluded so are we logged in?
			if ( ! $this->session->user ) {
				// Nope, keep hold of where we were asking for
				$this->session->referrer = '/'.$this->_request->getControllerName().'/'.$this->_request->getActionName();
				$this->log->debug( 'Setting Referrer to Controller : ' . $this->_request->getControllerName() . ' | Action : ' . $this->_request->getActionName() );
				// and forward the request to the login page
				$this->_forward( 'login', 'user' );
			}
		}
	}

	protected function renderModelForm( $action, $submitText = 'Submit' )
	{
		$this->form->setAction( $action );
		$this->form->addElement( 'submit', $submitText );
		$this->view->form = $this->form;
		echo $this->_response->setBody($this->view->render($this->templatesFolder."/home.tpl.php"));
		exit;
	}

	protected function getRecipe()
	{
		if ( $this->_getParam( 'recipe_id' ) ) {
			
			$r = new Recipe();
			$rowset = $r->find( $this->_getParam( 'recipe_id' ) );

			if ( ! $rowset )
				$this->_redirect( '/recipe/index' );

			$this->recipe = $rowset->current();
		}
	}

}
