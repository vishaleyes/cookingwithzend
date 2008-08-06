<?php

/**
 * 
 *  E-mail validation controller. Sends out verification e-mail and verifies them
 *  @author punky
 *  
 *  @todo Alter database schema to add confirmed field?
 *  @todo Add add the confirmed/unconfirmed code
 *  @todo Change email body/headers
 *  
 */

class EmailController extends DefaultController  
{

	/**
	 * This happens before the page is dispatched
	 */
	
	const SALT = "aSalt";

	public function preDispatch()
	{
		// People should be logged in to verify.
		$this->loggedIn( );
	}

	/**
	 * Setup this controller specifically and then call the parent
	 */

	public function init()
	{

		parent::init();
	}

	/**
	 * Main index page
	 */

	public function indexAction()
	{
	}

	/**
	 * Sends out e-mail to use to verify e-mail
	 * @todo set e-mail message body & set return e-mails. Possibly extra headers.
	 */

	public function sendAction()
	{
	
	$email = $this->session->user['email'];
	
	$verificationCode = $this->getVerificationCode($email);
		
	$message = "<html><body>Thank you for registering with us.\n\nVerification code: <a href=\"http://" . $_SERVER["HTTP_HOST"] . "/email/confirm/code/$verificationCode\">$verificationCode</a></body></html>";
	$headers .= 'From: Recipes <mail@punky.name>' . "\r\n";
	
	$headers .= "Message-ID: <".$now."root@".$_SERVER['SERVER_NAME'].">"."\r\n";
	
	// Only for HTML emails
	$headers .= 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	
	/*
	 * Uncomment to enable emails.
	 */ 
	//mail($email,"Email verification",$message,$headers);
	
	echo $this->_response->setBody($message);				
	
	
	}
	

	/*
	 * Generate verification code to be e-mailed. Code is mixed hash of the email and salt defined above.
	 */

	public function getVerificationCode($emailAddress)
	{
		
		return (MD5(MD5($emailAddress) . MD5(self::SALT)));	
		
	}
	
	public function confirmAction()
	{
		
		$email = $this->session->user['email'];
		$code = $this->_getParam('code');
		
		if ( strcasecmp($code,$this->getVerificationCode($email)) == 0 )
		{
			// Code matches, update DB
			echo $this->_response->setBody("confirmed");
		}
		else
		{
			// Code doesn't match, bitch at user.
			echo $this->_response->setBody("NOT confirmed");
		}
		
	}
	
	
	/**
	 * We are existing after the action is dispatched
	 */

	public function postDispatch() {
		exit;
	}
	
}

