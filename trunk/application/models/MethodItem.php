<?php

class MethodItem extends Recipe_Model_GenericModel {
	
	public function getFormElements()
	{
		$elements = array();
		$e = new Zend_Form_Element_Textarea( 'description' );

		$stripTags = new Zend_Filter_StripTags();

		$e->setRequired( true )
		  ->setLabel( 'Description' )
		  ->setAttrib( 'class', 'fck' )
		  ->addFilter( $stripTags );
		$elements[] = $e;

		return $elements;
	}

}
