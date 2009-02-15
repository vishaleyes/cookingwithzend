<?php

class Models_Recipe {
	
	public function getFormElements()
	{
		$elements = array();
		$e = new Zend_Form_Element_Text( 'name' );
		$e->setLabel( 'Name' )
		  ->setRequired( true )
		  ->addValidator( new Zend_Validate_NotEmpty(), true )
		  ->addValidator( new Zend_Validate_StringLength( array( 3, 255 ) ) );
		$elements[] = $e;

		$e = new Zend_Form_Element_Text( 'cooking_time' );
		$e->setLabel( 'Cooking Time (in minutes)' )
		  ->setRequired( true )
		  ->addValidator( new Zend_Validate_NotEmpty(), true )
		  ->addValidator( new Zend_Validate_Int() );
		$elements[] = $e;

		$e = new Zend_Form_Element_Text( 'preparation_time' );
		$e->setLabel( 'Preparation Time (in minutes)' )
		  ->setRequired( true )
		  ->addValidator( new Zend_Validate_NotEmpty(), true )
		  ->addValidator( new Zend_Validate_Int() );
		$elements[] = $e;

		$e = new Zend_Form_Element_Text( 'serves' );
		$e->setLabel( 'Serves' )
		  ->setRequired( true )
		  ->addValidator( new Zend_Validate_NotEmpty(), true )
		  ->addValidator( new Zend_Validate_Int() )
		  ->addValidator( new Zend_Validate_GreaterThan(0) );
		$elements[] = $e;

		$e = new Zend_Form_Element_Text( 'difficulty' );
		$e->setLabel( 'Difficulty' )
		  ->setRequired( true )
		  ->addValidator( new Zend_Validate_NotEmpty(), true )
		  ->addValidator( new Zend_Validate_Int() )
		  ->addValidator( new Zend_Validate_Between( 1,10 ) );
		$elements[] = $e;
		
		$e = new Zend_Form_Element_Checkbox( 'freezable' );
		$e->setLabel( 'Freezable' );
		$elements[] = $e;

		return $elements;
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
