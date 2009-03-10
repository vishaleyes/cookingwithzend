<?php

class Models_User extends Models_GenericModel {

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
