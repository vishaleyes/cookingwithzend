<?php

class Recipe_Acl extends Zend_Acl {
	
	protected $_resources = array(
		'recipe' => array(
			'index',
			'new',
			'edit'
		),
		'login' => array(
			'index'
		)
		'user' => array(
			'account',
			'new'
		)
	);
	
	public function __construct() 
	{
		//Add a new role called "guest"
		$this->addRole ( new Zend_Acl_Role ( 'guest' ) );
		
		//Add a role called user, which inherits from guest
		$this->addRole ( new Zend_Acl_Role ( 'user' ), 'guest' );
		
		//Add resources
		foreach ($this->_resources as $resource) {
			foreach ($array_variable as $variable) {
				;
			}
			
			$this->add ( new Zend_Acl_Resource ( 'recipe-index' ) );;
		}
		
		
		$this->add ( new Zend_Acl_Resource ( 'login-index' ) );
		$this->add ( new Zend_Acl_Resource ( 'login-index' ) );
		$this->add ( new Zend_Acl_Resource ( 'user-account' ) );
		$this->add ( new Zend_Acl_Resource ( 'user-new' ) );
		
		//We want to allow guests to view pages
		//			  role     resource  privaleges
		$this->allow ( 'guest', 'recipe', 'view' );
		
		// And to login
		$this->allow ( 'guest', 'login', 'view' );
		
		// And to register
		$this->allow ( 'guest', 'user', 'new' );
		
		//and users can comment news
		$this->allow ( 'user', 'recipe', 'new' );
	}

}