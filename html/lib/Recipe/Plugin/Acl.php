<?php
class Recipe_Plugin_Acl extends Zend_Controller_Plugin_Abstract {
	
	private $_acl = null;
	
	public function __construct()
	{
		$this->_acl = new Zend_Acl();
		$this->_acl->addRole(new Zend_Acl_Role('guest'));
		$this->_acl->addRole(new Zend_Acl_Role('member'), 'guest');
		$this->_acl->addRole(new Zend_Acl_Role('admin'), 'member');
		
		$this->_log = Zend_Registry::get('log');
	}
	
	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{
		// Start with a blank user
		$user = new Models_User();
		// Is the user logged in?
		$instance = Zend_Auth::getInstance();
		if ($instance->hasIdentity())
		{
			$user = $instance->getIdentity();
			$this->_log->debug('user is logged in and is a : '.$user->getRoleId());
		}
		
		$logString = $request->getControllerName() . '/' . $request->getActionName();
		$this->_log->debug('Trying to access : '.$logString);
		
		$resource = $this->_getResource($request);
		// If the resource returned is not a resource then we do not need to check anything
		if( !($resource instanceof Zend_Acl_Resource_Interface))
			return true;
		
		$this->_log->debug('Adding Rsource '.$resource->getResourceId());
		$this->_acl->addResource( $resource->getResourceId() );
			
		// Okay so we need to check what they are trying to access
		// Its going wrong here I think, the allows dont seam to be working?
		switch($request->getActionName())
		{
			case 'edit':
			case 'delete':
				$this->_acl->allow( $user, $resource, $request->getActionName(), new Recipe_Acl_CanAmmendAssertion());
				break;
			default:
				return true;
		}
		
		if(!$this->_acl->isAllowed($user, $resource, $request->getActionName())) {
			//If the user has no access we send him elsewhere by changing the request
			$request->setControllerName('login')
				->setActionName('index');
    	}
	}
	
	public function _getResource($request)
	{
		$resource = null;
		switch($request->getControllerName())
		{
			case 'recipe':
				$resource = new Models_Recipe($request->getParam('id'));
				break;
			case 'rating':
				$resource = new Models_Rating($request->getParam('id'));
				break;
		}
		return $resource;
	}
	
}
