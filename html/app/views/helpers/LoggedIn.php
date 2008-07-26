<?php

class Zend_View_Helper_LoggedIn
{

	public function loggedIn()
	{
		$this->session = Zend_Registry::get('session');
		if( $this->session->user )
			return true;
			
		return false;
	}

}