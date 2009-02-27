<?php

class Models_Recipe extends GenericModel{

	public function isOwner( $recipeId )
	{
		$session = Zend_Registry::get( 'session' );
		if ( ! $session->user )
			return false;
		
		$select = $this->select()
			->from( 'recipes' )
			->where( 'id = ?', $recipeId )
			->where( 'creator_id = ?', $session->user['id']);

		$row = $this->fetchRow();
		if ( ! $row ) {
			$this->log->info( 'No row' );
			return false;
		}

		if ( $session->user['id'] == $row->creator_id )
			return true;

		return false;
	}	

}
