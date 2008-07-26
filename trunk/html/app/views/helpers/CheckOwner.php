<?php

class Zend_View_Helper_CheckOwner
{

	public function checkOwner( $ownerId )
	{
		$session = Zend_Registry::get('session');
		if ( $session->user['id'] == $ownerId )
			return true;
		
		return false;
	}

}