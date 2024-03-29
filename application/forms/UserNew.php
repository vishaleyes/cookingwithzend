<?php

class Recipe_Form_UserNew extends Zend_Form
{

	public function init()
	{
		// set the method for the display form to POST
		$this->setMethod('post');

		// add an email element
		$this->addElement('text', 'email', array(
			'label'      => 'E-mail:',
			'required'   => true,
			'filters'    => array('StringTrim'),
			'validators' => array(
				new Zend_Validate_NotEmpty(),
				new Zend_Validate_EmailAddress()
			)
		))
		->addElement('password', 'password', array(
			'label'      => 'Password:',
			'required'   => true,
			'filters'    => array('StringTrim'),
			'validators' => array(
				new Zend_Validate_NotEmpty(),
				new Zend_Validate_Alnum(),
				new Zend_Validate_StringLength( array(3,255) )
			)
		))
		->addElement('text', 'name', array(
			'label'      => 'Name:',
			'required'   => true,
			'filters'    => array('StringTrim'),
			'validators' => array(
				new Zend_Validate_NotEmpty(),
				new Zend_Validate_Alnum(),
				new Zend_Validate_StringLength( array(3,255) )
			)
		))
		->addElement('submit', 'submit');
	}
}