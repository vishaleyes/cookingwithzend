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

class Recipe_Email extends Zend_Mail
{
	const FROM_EMAIL = "admin@simplcook.org";
	const FROM_NAME  = "SimplyCook.org";

	public function __construct( $to, $toName = '', $subject = '', $encoding = 'UTF-8' )
	{
		parent::__construct($encoding);

		$this->log = Zend_Registry::get( 'log' );
		$this->addTo($to, $toName);

		$this->setFrom(self::FROM_EMAIL, self::FROM_NAME);

		$this->setSubject($subject);
#		$this->setEncoding($encoding);
		$this->html = false;
	}
	
	/**
	 * Creates a view object to use as the text body
	 * @param string $template
	 */

	public function setTemplate($template) {
		$this->view = new Zend_View();
		$this->view->setScriptPath( APPLICATION_PATH . '/views/templates/email/');
		$this->template = $template;
	}

	/**
	 * Returns the current template
	 * @return string $template
	 */

	public function getTemplate($template) {
		return $this->template;
	}
	
	/**
	 * Uses Zend_Mail to send off the mail
	 * @param string $textBody
	 */

	public function sendMail($txtBody = null) {

		if (!$txtBody) {
			if ($this->template)
			{
				$txtBody = $this->view->render($this->template);
			}
		}
		
		$this->html ? $this->setBodyTxt(strip_tags( $txtBody )) : $this->setBodyHtml($txtBody);
		$this->log->debug( 'Sending email to ' . $this->_to[0] . ' using template ' . $this->template );

		try {
			if ($this->send()) return true;
		} catch (Exception $e) {
			$this->log->crit($e->getMessage() . "\n" . $e->getTraceAsString());
			return false;
		}

		return false;

	}

}
	
	
