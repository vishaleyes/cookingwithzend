<?php

class Models_User extends Models_GenericModel
{
	
	public function getUserByEmail($email)
	{
		$select = $this->db->select()
			->from('users')
			->joinLeft('acl_roles', 'users.role_id = acl_roles.id', array('role' => 'acl_roles.name'))
			->where('email = ?', $email);
		$stmt = $this->db->query($select);
		$rowSet = $stmt->fetchAll();
		if ($rowSet)
			return $rowSet[0];
		return false;
	}
	
	/**
	 * Login for the User, this sends the username/password to the Auth Adapter
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
	
	/**
	 * Prepares a forgotten password mail and sends it out the current user
	 * @return bool
	 */

	public function forgottenPasswordMail()
	{
		$newpass = $this->generatePassword();
		$this->password = new Zend_Db_Expr('PASSWORD("'.$newpass.'")');
		$this->save();
		
		$e = new Email( $this->email, $this->name, 'Forgotten Password' );
		$e->setTemplate( 'forgotten-password.phtml' );
		$e->view->password = $this->password;

		return $e->sendMail();
	}

	/**
	 * Sends out e-mail to use to verify e-mail
	 * @return bool
	 */

	public function sendConfirmationEmail($row)
	{
		$e = new Recipe_Email( $row['email'], $row['name'], 'Registration' );
		$e->setTemplate( 'user-registration.phtml' );

		$e->view->verificationURL = 'http://' . $_SERVER['HTTP_HOST'] . '/user/confirm/code/' . $row['code'];
	
		return $e->sendMail();
	}
	
	/**
	 * Used to check the status of the user, based on the ENUM of the DB field
	 * @return string
	 */

	public function checkStatus($status)
	{
		$message = '';

		switch ($status)
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
	 * Generates a password between 6 and 12 characters
	 * @return string
	 */

	private function generatePassword()
	{
	    $chars = "1234567890abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$i = 0;
		$length = mt_rand( 6, 12 );
	    $password = "";
	    while ($i <= $length) {
			$password .= $chars{mt_rand(0,strlen($chars))};
			$i++;
		}

		return $password;
	}
	
}
