<?php

class Models_Measurement extends Models_GenericModel 
{
	
	/**
     * This is to replace the _form_fields_config, I find it easier to follow - CL
     */

	public function getFormElements()
	{
		$elements = array();
		$e = new Zend_Form_Element( 'text' );
		$e->setName( 'measurement_name')
		  ->setAttrib( 'id', 'measurement-name' )
		  ->setLabel( 'Measurement' )
		  ->addValidator( new Zend_Validate_Alnum(), true );
		$elements[] = $e;

		$e = new Zend_Form_Element( 'text' );
		$e->setName( 'measurement_abbr')
		  ->setAttrib( 'id', 'measurement-abbr' )
		  ->setLabel( 'Measurement Abbr' )
		  ->addValidator( new Zend_Validate_Alnum(), true );
		$elements[] = $e;
		
		return $elements;
	}


}
