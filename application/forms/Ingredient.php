<?php

class Recipe_Form_Ingredient extends Zend_Form
{

	public function init()
	{
		$this->addElement('text', 'name', array(
			'label'      => 'Name:',
			'title'      => 'The ingredient name',
			'required'   => true,
			'filters'    => array('StringTrim'),
			'validators' => array(
				new Zend_Validate_Alnum(true),
				new Zend_Validate_NotEmpty(),
				new Zend_Validate_StringLength( array( 3, 255 ) )
			)
		))
		->addElement('text', 'measurement', array(
			'label'      => 'Measurement:',
			'title'      => 'Tbsp? Tsp? G? Kg?',
			'filters'    => array('StringTrim'),
			'validators' => array(
				new Zend_Validate_Alnum(true),
				new Zend_Validate_NotEmpty(),
				new Zend_Validate_StringLength( array( 3, 255 ) )
			)
		))
		->addElement('text', 'quantity', array(
			'label'      => 'Quantity:',
			'title'      => 'More than one of these items?',
			'filters'    => array('StringTrim'),
			'validators' => array(
				new Zend_Validate_Int()
			)
		))
		->addElement('text', 'amount', array(
			'label'      => 'Amount:',
			'title'      => 'How much of it? e.g. 250',
			'filters'    => array('StringTrim'),
			'validators' => array(
				new Zend_Validate_Alnum(true),
				new Zend_Validate_NotEmpty(),
				new Zend_Validate_StringLength( array( 3, 255 ) )
			)
		))
		->addElement('hidden', 'recipe_id')
		->addElement('submit', 'submit');
	}
}