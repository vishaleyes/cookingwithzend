<?php

class Models_Recipe extends Models_GenericModel{

	/**
	 * Find the recipe
	 * @param int $id ID of the recipe
	 */

	public function fetchRecipe($id)
	{
		// Fetch the recipe being requested
		$rowSet = $this->table->find( $id );
		Zend_Registry::get('log')->info(var_export($rowSet, true));
		
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

}
