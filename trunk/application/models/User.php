<?php

class Models_User extends Models_GenericModel implements Zend_Acl_Resource_Interface, Zend_Acl_Role_Interface
{
	protected $_ownerUserId = null;
	
	protected $_data = array(
		'id'             => null,
		'name'           => null,
		'last_login'     => null,
		'status'         => null,
		'comments_count' => 0,
		'ratings_count'  => 0,
		'recipes_count'  => 0,
		'role'           => null,
		'preferences'    => array()
	);
	
	public function getResourceId()
	{
		return 'user';
	}
	
	/**
	 * Retrieves the roleID, required by the inclusion of implements Zend_Acl_Role_Interface
	 * 
	 * @return string
	 */
	
	public function getRoleId()
	{
		if ($this->_data['role'] == null)
			return 'guest';
		return $this->_data['role'];
	}
	
	/**
	 * Retieves the user information if you supply an email address
	 * 
	 * @param string $email
	 * @return array|false 
	 */
	
	public function getUserByEmail($email)
	{
		if ( !$row = parent::getSingleByField('email', $email) )
			return false;
		$this->_ownerUserId = $this->_data['id'];
		return true;
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
		$config = Zend_Registry::get( 'config' );
		$auth = Zend_Auth::getInstance();

		// @todo Move this to bootstrap
		$authAdapter = new Zend_Auth_Adapter_DbTable( $this->db );
		$authAdapter->setTableName( $config->authentication->tableName )
			->setIdentityColumn( $config->authentication->identityColumn )
			->setCredentialColumn( $config->authentication->credentialColumn )
			->setCredentialTreatment( $config->authentication->credentialTreatment )
			->setIdentity( $email )
			->setCredential( $password );
	
		$result = $auth->authenticate( $authAdapter );

		if ( ! $result->isValid() )
			return join(',', $result->getMessages());
			
		$this->getUserByEmail($auth->getIdentity());
		$msg = $this->checkStatus();
		
		if ( $msg != '' )
		{
			$auth->clearIdentity();
			$this->log->info('User '.sq_brackets( $this->_data['name'] ).' tried to login but got ' . sq_brackets( $msg ) );
			return $msg;
			
		}
		
		$this->table->update(
			array('last_login' => new Zend_Db_Expr('NOW()')),
			'id = '.$this->_data['id']
		);
		
		// @todo get the preferences
		$up = new Models_UserPreferences($this->_data['id']);
		$this->_data['preferences'] = $up;
		
		$auth->getStorage()->write($this);
		return true;
	}
	
	/**
	 * Prepares a forgotten password mail and sends it out the current user
	 * @return bool
	 */

	public function forgottenPasswordMail($row)
	{
		// Reset the password
		$password = $this->generatePassword();
		$row['password'] = new Zend_Db_Expr('PASSWORD("'.$password.'")');
		// remove the role from the row because we cannot update that
		unset($row['role']);
		
		$this->table->update($row, 'id = '.$row['id']);
				
		$e = new Recipe_Email( $row['email'], $row['name'], 'Forgotten Password' );
		$e->setTemplate( 'forgotten-password.phtml' );
		$e->view->password = $password;

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

		$e->view->verificationURL = 'http://' . $_SERVER['HTTP_HOST'] . '/user/confirm/code/' . $row['confirm'];
	
		return $e->sendMail();
	}
	
	/**
	 * Used to check the status of the user, based on the ENUM of the DB field
	 * @return string
	 */

	public function checkStatus()
	{
		$message = '';

		switch ($this->_data['status'])
		{
			case 'banned':
				$message = 'Your account has been banned, you need to get in touch with us to find out why';
				break;
			case 'suspended':
				$message = 'Your account has been suspended, you should of been mailed the reason';
				break;
			case 'pending':
				$message = 'You need to fully activate your account to continue, maybe you need a <a href="/login/gsend-confirmation">confirmation e-mail</a>';
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

	private function _generatePassword()
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
