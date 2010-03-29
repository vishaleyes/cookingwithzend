<?php
class Recipe_Plugin_Acl extends Zend_Controller_Plugin_Abstract {
	
	private $_acl = null;
	
	public function __construct()
	{
		$this->_acl = new Zend_Acl();
	}
	
	public function preDispatch(Zend_Controller_Request_Abstract $request) {
		
		// Start with a blank user
		$user = new Models_User();
		// Is the user logged in?
		$instance = Zend_Auth::getInstance();
		if ($instance->hasIdentity())
		{
			$user = $instance->getIdentity();
		}
		
		$resource = $this->_getResource($request);
		// If the resource returned is not a resource then we do not need to check anything
		if( !($resource instanceof Zend_Acl_Resource_Interface))
			return true;
			
		// Okay so we need to check what they are trying to access
		switch($request->getActionName())
		{
			case 'edit':
			case 'delete':
				$this->_acl->allow( $user, $resource, 'view', new Recipe_Acl_CanAmmendAssertion() );
			default:
				$this->_acl->allow( $user, $resource);
		}
		
		if(!$this->_acl->isAllowed($user, $resource)) {
		//If the user has no access we send him elsewhere by changing the request
			$request->setControllerName('login')
				->setActionName('index');
    	}
	}
	
	public function checkActions()
	{
		
	}
	
	private function _getResource($request)
	{
		$resource = null;
		switch ($request->getControllerName())
		{
			case 'recipe':
				$resource = new Models_Recipe($request->_getParam('id'));
				break;
			default:
				// This controller has no resourse associated with it;
				break;
		}
		return $resource
	}
}
