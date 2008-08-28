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
	const FROM_EMAIL = "email@site.name";

	var $files;
	var $cc;
	var $template;
	var $html;


	public function __construct( $to, $toName = '', $subject = '', $from = '', $fromName = '', $encoding = 'UTF-8' )
	{
		parent::__construct();

		$this->log = Zend_Registry::get( 'log' );
		$this->addTo($to, $toName);

		$from = ( empty( $from ) ? self::FROM_EMAIL : $from );
		$this->setFrom($from, $fromName);

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
		$this->view->setScriptPath('app/views/templates/email/');
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
	 * Sets a HTML flag or not to decide if the email should be sent in HTML or not
	 * @param bool $var
	 */

	public function setHTML( $var )
	{
		$this->html = $var;
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
		
		if ( $this->html ) {
			$this->setBodyTxt(strip_tags( $txtBody ));
		} else {
			$this->setBodyHtml($txtBody);
		}

		// add attachments
		if (count($this->files) > 0) {
			foreach($this->files as $file) {
				$this->createAttachment($file);
			}
		}

		// add cc
		if (count($this->cc) > 0) {
			foreach($this->cc as $cc) {
				$this->addCc($cc);
			}
		}

		$this->log->debug( var_export( $this, true ) );

		try {
			if ($this->send()) return true;
		} catch (Exception $e) {
			$this->log->crit($e->getMessage() . "\n" . $e->getTraceAsString());
			return false;
		}

		return false;

	}

}
	
	
