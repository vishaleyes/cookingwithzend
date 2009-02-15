<?php

/**
* 
*/
class Models_DbTable_User extends Zend_Db_Table_Abstract
{
	protected $_name = "users";
	protected $_primary = "id";
	protected $_rowClass = "UserRow";

	# Primary does Auto Inc
	protected $_sequence = true;

	protected $_dependentTables = array('Recipe');
	
	public function insert( $params )
	{
		$params['created'] = new Zend_Db_Expr('NOW()');
		$params['updated'] = new Zend_Db_Expr('NOW()');
		$params['last_login'] = new Zend_Db_Expr('NOW()');
		
		return parent::insert( $params );
	}
	
}
