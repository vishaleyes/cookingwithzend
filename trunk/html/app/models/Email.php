<?php

/**
 * 
 *  E-mail model - used to send e-mails to the user from the sight
 *  @author punky
 *  
 *  @todo Retrieve settings from config.xml
 *  @todo Change from address email & body/headers
 *  
 */


class Email extends Zend_Mail
{
	
		const SALT = "aSalt";
		const FROM_EMAIL = "From: Recipes Site Name <email@site.name>";
	

	// May be able to delete this
	function __construct( $prefetch = true )
	{
			
	}
	
	
	/**
	 * Sends out e-mail to use to verify e-mail
	 * @todo set e-mail message body & set return e-mails. Possibly extra headers.
	 */

	public function sendConfirmationEmail($email)
	{
	
	
	$verificationCode = $this->getVerificationCode($email);
	
	$u = new User();
	$u = $u->getByEmail($email);
	$userId = $u->id;
		
	$message = "<html><body>Thank you for registering with us.\n\nVerification code: <a href=\"http://" . $_SERVER["HTTP_HOST"] . "/user/confirm/userid/$userId/code/$verificationCode\">$verificationCode</a></body></html>";
	$headers .= self::FROM_EMAIL . "\r\n";
	
	$headers .= "Message-ID: <".$now."root@".$_SERVER['SERVER_NAME'].">"."\r\n";
	
	// Only for HTML emails
	$headers .= 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	
	
	return (mail($email,"Email verification",$message,$headers));
	}
	
	
	/*
	 * Generate verification code to be e-mailed. Code is mixed hash of the email and salt defined above.
	 */

	public function getVerificationCode($emailAddress)
	{
		
		return (MD5(MD5($emailAddress) . MD5(self::SALT)));	
		
	}
	

		


	
}
	
	