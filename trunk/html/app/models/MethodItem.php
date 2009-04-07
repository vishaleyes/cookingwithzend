<?php

class MethodItem extends Models_GenericModel {
	
	// Form elements for add/edit
	public $_form_fields_config = array(
		array( 'textarea', 'description', array(
			'required' => true,
			'label'    => 'Description'
		) ),
	);

	public function getFormElements()
	{
		$elements = array();
		$e = new Zend_Form_Element_Textarea( 'description' );

		$stripTags = new Zend_Filter_StripTags();
		$stripTags->setTagsAllowed( array( 'p', 'a', 'img', 'strong', 'b', 'i', 'em', 's', 'del' ) );
		$stripTags->setAttributesAllowed( array( 'href', 'target', 'rel', 'name', 'src', 'width', 'height', 'alt', 'title' ) );

		$e->setRequired( true )
		  ->setLabel( 'Description' )
		  ->setAttrib( 'class', 'fck' )
		  ->addFilter( $stripTags );
		$elements[] = $e;

		return $elements;
	}

}
