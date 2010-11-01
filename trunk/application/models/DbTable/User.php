<?php

/**
* 
*/
class Models_DbTable_User extends Zend_Db_Table_Abstract
{
	protected $_name = "users";
	protected $_primary = "id";

	const SALT = "aSalt";
	
	# Primary does Auto Inc
	protected $_sequence = true;

	protected $_dependentTables = array(
		'Models_DbTable_Recipe',
		'Models_DbTable_Comment'
	);
	
	public function insert( array $params )
	{
		$params['created'] = new Zend_Db_Expr('NOW()');
		$params['updated'] = new Zend_Db_Expr('NOW()');
		$params['last_login'] = new Zend_Db_Expr('NOW()');
		
		// generate a confirmation code
		$params['confirm'] = $this->getVerificationCode($params);
		
		return parent::insert( $params );
	}
	
	/**
	 * Generate verification code to be e-mailed. Code is mixed hash of the email and salt defined above.
	 * @return string
	 */

	public function getVerificationCode(array $params)
	{
		return (MD5(MD5($params['email'] . $params['created']) . MD5(self::SALT)));	
	}
	
}
