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
		$this->partialsFolder = '/partials';
		$this->templatesFolder = '/templates';
		$this->includesFolder = '/includes';
		
		// Setting up view's properties
		$this->view->setScriptPath('app/views');
		$this->view->addHelperPath('notech/view/helper', 'Notech_View_Helper');

		$this->view->online = $this->config->online;
		$this->view->release_no = $this->release_no;
		$this->view->version = $this->environment;
		$this->view->session = $this->session;
		
		$this->message = $this->_helper->getHelper('FlashMessenger');	
		$this->view->message = $this->message;

		$this->view->action = $this->_getParam( 'action' );
		$this->view->controller = $this->_getParam( 'controller' );
		$this->view->partialsFolder = $this->partialsFolder;
		$this->view->pagesFolder = $this->pagesFolder;
		$this->view->templatesFolder = $this->templatesFolder;
		$this->view->includesFolder = $this->includesFolder;

		// Setting up some other properties
		$this->getRecipe();
	}

	/**
	 * Checks to ensure that a user is logged in to perform the action requested.
	 * If they are not they are re-directed to the login page
	 *
	 * @param $exclusions array() The pages you don't want to check
	 */

	protected function loggedIn( $exclusions = array() )
	{
		// figure out whats being requested
		$action = $this->_request->getActionName();
		$controller = $this->_request->getControllerName();
		
		// we dont want to put any of these keys in the referrer
		$keys = array( 'controller', 'action', 'email', 'password', 'module', 'Login' );
		
		if ( ( $action != 'login' ) && ( $controller != 'ajax' ) ) {
			// Nope, keep hold of where we were asking for
			$this->session->referrer = '/'.$controller.'/'.$action;
			foreach( $this->_getAllParams() as $k => $v )
			{
				if ( in_array( $k, $keys ) )
					continue;
					
				$this->session->referrer .= '/' . $k . '/' . $v;
			}
			// $this->log->debug( 'Setting Referrer to : ' . $this->session->referrer );
		}

		if ( ! in_array( $action, $exclusions ) )
		{
			// Were not excluded so are we logged in?
			if ( ! $this->session->user ) {
				// and forward the request to the login page
				$this->_forward( 'login', 'user' );
			}
		}

	}

	protected function pendingAccount( $exclusions = array() )
	{
		$action = $this->_request->getActionName();
		if ( ! in_array( $action, $exclusions ) ) {
			// Are we logged in but not confirmed
			if ( $this->session->user['status'] == 'pending' ) {
				$this->message->setNamespace( 'error' );
				$this->message->addMessage( 'Your account has not been confirmed, please click the e-mail you received from us do you need it <a href="/user/sendconfirmation/'.$this->name.'">resending?</a>');
				$this->message->resetNamespace();
				$this->_redirect('/');
			}
		}
	}

	/**
	 * Checks to ensure that the correct user is logged to perform the
	 * action requested.  If they are not they are re-directed to either the
	 * page they came from or '/'
	 *
	 * @param $exclusions array() The pages you don't want to check
	 */

	protected function authorised( array $inclusions )
	{
		// figure out whats being requested
		$action = $this->_request->getActionName();
	
		// If this is an action we need to worry about
		if ( in_array( $action, $inclusions ) )
		{
			// Is the user allowed to access this?
			if ( $this->session->user['id'] != $this->recipe->creator_id ) {
				// $this->session->error = 'You cannot change a recipe owned by someone else';
				// Nope, keep hold of where we came from
				$this->log->info( var_export($this->_request, true) );
				// Redirect to there or
				
				// Redirect to /
				$this->_redirect( '/' );
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

	/**
	 * Retireves the currently requested recipe (if there is one being requested)
	 */

	protected function getRecipe()
	{

		if ( $this->_getParam( 'recipe_id' ) > 0 ) {
			
			$r = new Recipe();
			$rowset = $r->find( $this->_getParam( 'recipe_id' ) );

			if ( ! $rowset )
				$this->_redirect( '/recipe/index' );

			$this->recipe = $rowset->current();
		}
	}

}
