<?php

class Models_Recipe extends GenericModel{

	/**
	 * @param int $id ID of the recipe
	 */

	public function fetchRecipe($id)
	{
		// Fetch the recipe being requested
		$rowSet = $this->table->find( $id );
		
		// Couldnt find it?  Oh dear we best throw an error
		// @todo Move this test to somwhere else when I figure out the best place for it
		if (!$rowSet)
		{
			$this->message->setNamespace( 'error' );
			$this->message->addMessage( 'Unable to find recipe with id ' . $id );
			$this->message->resetNamespace();
			$this->_redirect( '/recipe/index' );
		}
		$recipe = $rowSet->current();
		return $recipe;
	}

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
