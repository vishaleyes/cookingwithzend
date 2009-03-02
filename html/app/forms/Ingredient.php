<?php

class Forms_Ingredient extends Zend_Form
{

	public function init()
	{
		$this->addElement('text', 'name', array(
			'label'      => 'Name:',
			'required'   => true,
			'filters'    => array('StringTrim'),
			'validators' => array(
				new Zend_Validate_Alnum(true),
				new Zend_Validate_NotEmpty(),
				new Zend_Validate_StringLength( array( 3, 255 ) )
			)
		))
		->addElement('hidden', 'id')
		->addElement('submit', 'submit');
	}
}