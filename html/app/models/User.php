<?php

class User extends Zend_Db_Table_Abstract {

	protected $_name = "users";
	protected $_primary = "id";
	protected $_rowClass = "UserRow";

	# Primary does Auto Inc
	protected $_sequence = true;

	protected $_dependentTables = array('Recipe');

	public function getFormElements()
	{
		$elements = array();
		$e = new Zend_Form_Element( 'text' );
		$e->setRequired( true )
		  ->setLabel( 'Username' )
		  ->setName('name')
		  ->setAttrib( 'id', 'user-name' )
		  ->addValidator( new Zend_Validate_NotEmpty(), true )
		  ->addValidator( new Zend_Validate_Alnum(), true )
		  ->addValidator( new Zend_Validate_StringLength( array(3,255) ) );
		$elements[] = $e;

		$e = new Zend_Form_Element( 'text' );
		$e->setRequired( true )
		  ->setLabel( 'Email' )
		  ->setName( 'email' )
		  ->addValidator( new Zend_Validate_NotEmpty(), true )
		  ->addValidator( new Zend_Validate_EmailAddress(), true );
		$elements[] = $e;
		
		$e = new Zend_Form_Element_Password('password');
		$e->setRequired( true )
		  ->setLabel( 'Password' )
		  ->addValidator( new Zend_Validate_NotEmpty(), true )
		  ->addValidator( new Zend_Validate_Alnum(), true )
		  ->addValidator( new Zend_Validate_StringLength( array(3,255) ) );
		$elements[] = $e;

		$e = new Zend_Form_Element_Text( 'open_id' );
		$e->setRequired( true )
		  ->setLabel( 'OpenID' )
		  ->setAttrib( 'id', 'open-id' );
		
		$elements[] = $e;
		
		return $elements;
	}

	// May be able to delete this
	function __construct( $prefetch = true )
	{
		if ( ! $prefetch === false ) unset( $_rowClass );
		$this->db = Zend_Registry::get("db");
		Zend_Db_Table_Abstract::setDefaultAdapter($this->db);
		
		$this->log = Zend_Registry::get('log');
		
		$this->_setup();
	}

	public function insert( $params )
	{
		$params['created'] = new Zend_Db_Expr('NOW()');
		$params['updated'] = new Zend_Db_Expr('NOW()');
		$params['last_login'] = new Zend_Db_Expr('NOW()');
		
		return parent::insert( $params );
	}
	
	public function getByEmail( $email )
	{
		$user = null;
		$select = $this->select()->where( 'email = ?', $email );
		$user = $this->fetchRow( $select );
		return $user;
	}
	
	public function getByOpenID( $id )
	{
		$user = null;
		$select = $this->select()->where( 'openid = ?', $id );
		$user = $this->fetchRow( $select );
		return $user;
	}

}
