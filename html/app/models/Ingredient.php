<?php

class Models_Ingredient {
	

	/**
     * This is to replace the _form_fields_config, I find it easier to follow - CL
     */
	
	public function getFormElements()
	{
		$elements = array();
		$e = new Zend_Form_Element( 'text' );
		$e->setName( 'ingredient_name')
		  ->setAttrib( 'id', 'ingredient-name' )
		  ->setLabel( 'Name' )
		  ->setRequired( true )
		  ->addValidator( new Zend_Validate_Alnum(true), true )
          ->addValidator( new Zend_Validate_StringLength( array(3,255) ), false );
		$elements[] = $e;
		
		return $elements;
	}

}
