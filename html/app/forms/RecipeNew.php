<?php

/**
	* 
	*/
class Forms_RecipeNew extends Zend_Form
{

	public function init()
	{
		// set the method for the display form to POST
		$this->setMethod('post');

		// add an email element
		$this->addElement('text', 'name', array(
			'label'      => 'Name:',
			'required'   => true,
			'filters'    => array('StringTrim'),
			'validators' => array(
				new Zend_Validate_NotEmpty(),
				new Zend_Validate_StringLength( array( 3, 255 ) )
			)
		))
		->addElement( 'text', 'cooking_time', array(
			'label'      => 'Cooking Time (in minutes):',
			'required'   => true,
			'filters'    => array('StringTrim'),
			'validators' => array(
				new Zend_Validate_NotEmpty(),
				new Zend_Validate_Int()
			)
		))
		->addElement( 'text', 'preparation_time', array(
			'label'      => 'Preparation Time (in minutes)',
			'required'   => true,
			'filters'    => array('StringTrim'),
			'validators' => array(
				new Zend_Validate_NotEmpty(),
				new Zend_Validate_Int()
			) 
		))
		->addElement( 'text', 'serves', array(
			'label'      => 'Serves',
			'required'   => true,
			'filters'    => array('StringTrim'),
			'validators' => array(
				new Zend_Validate_NotEmpty(),
				new Zend_Validate_Int(),
				new Zend_Validate_GreaterThan(0)
			) 
		))
		->addElement('text', 'difficulty', array(
			'label'      => 'Difficulty',
			'required'   => true,
			'validators' => array(
				new Zend_Validate_NotEmpty(),
				new Zend_Validate_Int(),
				new Zend_Validate_Between( 1, 10 )
			)
		))
		->addElement('checkbox', 'freezable', array(
			'label' => 'Freezable'
		));
	}
}