<?php

class User extends Zend_Db_Table_Abstract {

	protected $_name = "users";
	protected $_primary = "id";	   

	# Primary does Auto Inc
	protected $_sequence = true;

	protected $_dependentTables = array('Recipe');

	// Form elements for add/edit
	public $_form_fields_config = array(
		array( 'text', 'name', array(
			'required' => true,
			'label' => 'Username',
			'validators' => array(
				array( 'NotEmpty', true ),
				array( 'alnum' ),
				array( 'stringLength', false, array( 3, 255 ) ),
			)
		 ) ),
		array( 'text', 'email', array(
			'required' => true,
			'label' => 'Email',
			'validators' => array(
				array( 'NotEmpty', true ),
				array( 'EmailAddress' )
			)
		 ) ),
		array( 'password', 'password', array(
			'required' => true,
			'label' => 'Password',
			'validators' => array(
				array( 'NotEmpty', true ),
				array( 'alnum', true ),
				array( 'stringLength', false, array( 6, 255 ) ),
			)
		 ) ),
		
	);

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

}
