<?php

class Recipe_Acl extends Zend_Acl {

	public function __construct() 
	{
		// Add a new role called "guest"
		$this->addRole( new Zend_Acl_Role ( 'guest' ) );

		// Add a role called member, which inherits from guest
		$this->addRole( new Zend_Acl_Role( 'member' ), 'guest' );
		$this->addRole( new Zend_Acl_Role( 'admin' ), 'member' );

		// Resources
		$this->add( new Zend_Acl_Resource( 'error:error' ) );
		$this->add( new Zend_Acl_Resource( 'login:index' ) );
		
		$this->add( new Zend_Acl_Resource( 'recipe' ) );
		$this->add( new Zend_Acl_Resource( 'recipe:new' ) );
		$this->add( new Zend_Acl_Resource( 'recipe:edit' ) );
		$this->add( new Zend_Acl_Resource( 'recipe:view' ) );
		
		$this->add( new Zend_Acl_Resource( 'user:new' ) );

		//We want to allow guests to view pages
		//             role     resource  privaleges
		$this->allow( 'guest', 'recipe');
		$this->allow( 'guest', 'recipe:view');
		$this->allow( 'guest', 'error:error');
		$this->allow( 'guest', 'user:new');

		// Members can add recipes
		$this->allow( 'member', 'recipe:new');
		$this->allow( 'member', 'recipe:edit');
	}
	
}