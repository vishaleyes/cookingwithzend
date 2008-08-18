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
	
	/**
	 * Sends out e-mail to use to verify e-mail
	 * @todo set e-mail message body & set return e-mails. Possibly extra headers.
	 * @param $email string Email address
	 * @param $userid int id for the user
	 * @return bool
	 */

	public function sendConfirmationEmail($email,$userId)
	{
	
		$verificationCode = $this->getVerificationCode($email,$userId);
	
		$message = "<html><body>Thank you for registering with us.\n\nVerification code: <a href=\"http://" . $_SERVER["HTTP_HOST"] . "/user/confirm/$verificationCode\">$verificationCode</a></body></html>";
		$headers .= self::FROM_EMAIL . "\r\n";
	
		$headers .= "Message-ID: <".$now."mail@".$_SERVER['SERVER_NAME'].">"."\r\n";
	
		// Only for HTML emails
		$headers .= 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	
		return (mail($email,"Email verification",$message,$headers));
	}
	
	
	/*
	 * Generate verification code to be e-mailed. Code is mixed hash of the email and salt defined above.
	 * @param $email string Email address
	 * @param $userid int id for the user
	 * @return string
	 */

	public function getVerificationCode($emailAddress, $userId)
	{
		
		return (MD5(MD5($emailAddress) . MD5(self::SALT)) . "$userId");	
		
	}
	

		


	
}
	
	
