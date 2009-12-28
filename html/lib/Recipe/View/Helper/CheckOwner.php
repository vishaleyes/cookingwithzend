<?php

class Recipe_View_Helper_CheckOwner extends Zend_View_Helper_Abstract
{

	/**
	 * Returns a true or false if the ID you provide is the same as
	 * the id that is logged in.
	 * 
	 * @param int $id
	 * @return bool
	 */
	
	public function checkOwner( $id )
	{
		$auth = Zend_Auth::getInstance();
		if ( ! $auth->hasIdentity() )
			return false;
		
		$identity = $auth->getIdentity();
		return ( $identity['id'] == $id ? true : false );
	}

}