<?php

class Models_User extends GenericModel {

	/**
	 * Used to check the status of the user, based on the ENUM of the DB field
	 * @return string
	 */

	public function checkStatus()
	{
		$message = '';

		switch ($this->status)
		{
			case 'banned':
				$message = 'Your account has been banned, you need to get in touch with us to find out why';
				break;
			case 'suspended':
				$message = 'Your account has been suspended, you should of been mailed the reason';
				break;
			case 'pending':
			case 'admin':
			case 'active':
				break;
		}

		return $message;
	}

	/**
	 * @deprecated
	 */

	public function getByEmail( $email )
	{
		$user = null;
		$select = $this->select()->where( 'email = ?', $email );
		$user = $this->fetchRow( $select );
		return $user;
	}
	
	/**
	 * @deprecated
	 */
	
	public function getByOpenID( $id )
	{
		$user = null;
		$select = $this->select()->where( 'openid = ?', $id );
		$user = $this->fetchRow( $select );
		return $user;
	}
	
	/**
	 * @deprecated
	 */
	
	public function getByUserID( $id )
	{
		$user = null;
		$select = $this->select()->where( 'id = ?', $id );
		$user = $this->fetchRow( $select );
		return $user;
	}

	
	/**
	 * Login for the Employee, this sends the username/password to the Auth Adapter
	 *
	 * @param string $email
	 * @param string $password
	 * @return Zend_Auth_Result
	 */
	public function login( $email, $password )
	{
		$db = Zend_Registry::get( 'db' );
		$config = Zend_Registry::get( 'config' );
		$auth = Zend_Auth::getInstance();

		// @todo Move this to bootstrap
		$authAdapter = new Zend_Auth_Adapter_DbTable( $db );
		$authAdapter->setTableName( $config->authentication->tableName )
			->setIdentityColumn( $config->authentication->identityColumn )
			->setCredentialColumn( $config->authentication->credentialColumn )
			->setCredentialTreatment( $config->authentication->credentialTreatment )
			->setIdentity( $email )
			->setCredential( $password );

		return $auth->authenticate( $authAdapter );
	}

}
