<?php

/**
 * Default Controller - An Abstract class that extends Zend_Controller_Action
 * and should be used as a parent class for all other major controllers
 */

abstract class Recipe_Model_Controller extends Zend_Controller_Action
{

	/**
	 * Database object (for example Zend_Db_Adapter_Pdo_Mysql object)
	 * @var object
	 */
	protected $_db;

	/**
	 * Logger Object (usage : $this->log->log("Message to log", 0);)
	 * @var Zend_Log
	 */
	protected $_log;

	/**
	 * Session object (usage : $this->session->session_key = $session_value)
	 * @var Zend_Session
	 */
	protected $_session;
	
	/**
	 * Holder for the flash messenger that is used to tell users whats happening
	 * @var Zend_Controller_Action_Helper_FlashMessenger
	 */
	protected $_flashMessenger;
	
	/**
	 * The users identity
	 * @var array 
	 */
	protected $_identity;
	
	/**
	 * The users preferences
	 * @var obj
	 */
	protected $_prefs;
	
	/**
	 * The role of the current user
	 * @var string
	 */
	protected $_role;
	
	/**
	 * The ID that we are currently requesting
	 * @var int
	 */
	protected $_id;
	
	protected $_model;
	protected $_form;
	
	const PREFIX = 'Recipe_';
	
	public function init()
	{
		$this->_log = Zend_Registry::get('log');
		$this->_db = Zend_Db_Table::getDefaultAdapter();
		$this->_acl = Zend_Registry::get('acl');

		// Get the session for all controllers
		$this->_session = Zend_Registry::get('session');
		$this->view->session = $this->_session;
		
		// Setup the redirector
		$this->_redirector = $this->_helper->getHelper('Redirector');

		// Add the messenger
		$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
		$this->view->message = $this->_flashMessenger;
		
		$this->_id = $this->_getParam('id');
		
		$this->_role = 'guest';
		// If there is an identity store it in the controller and the view object
		$auth = Zend_Auth::getInstance();
		if ( $auth->hasIdentity() ) {
			$this->_identity = $auth->getIdentity();
			$this->_prefs = $this->_identity->preferences;
			$this->view->identity = $this->_identity;
			$this->_role = $this->_identity->role;
			$this->view->role = $this->_role;
		} else {
			$this->_prefs = new Recipe_Model_UserPreferences();
		}
		
	}
	
	/**
	 * Loads the relevant form in the forms directory
	 * 
	 * @param string $form Name of the Form
	 * @return Zend_Form
	 */

	public function getForm($formName = null)
	{
		if ( null === $formName )
			$formName = substr(get_class($this), 0, strpos(get_class($this), 'Controller'));
			
		$modelClass = self::PREFIX . 'Form_' . $formName;
		$this->_form = new $modelClass();
		$this->view->form = $this->_form;
		return $this->_form;
	}
	
	
	/**
	 * Returns a new model class, derived from the current controller
	 * @return obj
	 */
	public function getModel()
	{
		$modelName = substr(get_class($this), 0, strpos( get_class($this), 'Controller'));
		$modelClass = self::PREFIX . 'Model_' . $modelName;
		$this->_model = new $modelClass();
		return $this->_model;
	}
	
	/**
	 * Checks the parameters that are required
	 *
	 * @param array $requiredParams
	 * @return boolean
	 */
	public function checkRequiredParams( array $requiredParams )
	{
		$return = true;
		foreach ($requiredParams as $param)
		{
			if ( ! $this->_getParam($param) )
			{ 
				$this->_flashMessenger->addMessage( 'Unable to required field '.$param );
				$return = false;
			}
		}
		return $return;	
	}
	
}
