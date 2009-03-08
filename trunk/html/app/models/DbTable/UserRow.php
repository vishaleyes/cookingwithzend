<?php

class Models_DbTable_UserRow extends Zend_Db_Table_Row_Abstract {

	const SALT = "aSalt";
	
	public function adjustColumn( $column, $type = 'increase' )
	{
		switch($type){
			case 'increase':
				$newTotal = ($this->_data[$column] + 1);
				break;
			case 'decrease':
				$newTotal = ($this->_data[$column] - 1);
				break;
		}

		$table = $this->getTable();
		$where = $table->getAdapter()->quoteInto( 'id = ?', $this->id );
		$params[$column] = $newTotal;

		$table->update( $params, $where );
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
	 * Generate verification code to be e-mailed. Code is mixed hash of the email and salt defined above.
	 * @param $email string Email address
	 * @param $userid int id for the user
	 * @return string
	 */

	public function getVerificationCode()
	{
		
		return (MD5(MD5($this->email) . MD5(self::SALT)));	
		
	}


}
