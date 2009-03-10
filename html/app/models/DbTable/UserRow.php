<?php

class Models_DbTable_UserRow extends Zend_Db_Table_Row_Abstract {

	const SALT = "aSalt";
	
	/**
	 * Retrieves the number of recipes owned by a user
	 * @return int
	 */
	public function getRecipeCount() {
		$db = $this->getTable()->getAdapter();
		$select = $db->select()
			->from('recipes', array('recipe_count' => new Zend_Db_Expr('COUNT(id)')))
			->where('creator_id = ?', $this->id);
		$col = $db->fetchCol($select);
		return $col[0];
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
	 * Generates a password between 6 and 12 characters
	 * @return $password string
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

	/**
	 * Sends out e-mail to use to verify e-mail
	 * @return bool
	 */

	public function sendConfirmationEmail()
	{
	
		$verificationCode = $this->getVerificationCode();
		$e = new Email( $this->email, $this->name, 'Registration' );
		$e->setTemplate( 'user-registration.phtml' );

		$e->view->verificationURL = 'http://' . $_SERVER['HTTP_HOST'] . '/user/confirm/' . $verificationCode ;
	
		return $e->sendMail();
	}
	
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
	 * Generate verification code to be e-mailed. Code is mixed hash of the email and salt defined above.
	 * @return string
	 */

	public function getVerificationCode()
	{
		
		return (MD5(MD5($this->email) . MD5(self::SALT)));	
		
	}


}
