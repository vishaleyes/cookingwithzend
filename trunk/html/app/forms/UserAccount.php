<?php

class Forms_UserAccount extends Zend_Form
{

	public function init()
	{
		// set the method for the display form to POST
		$this->setMethod('post');

		// add an email element
		$this->addElement('text', 'email', array(
			'label'      => 'E-mail:',
			'filters'    => array('StringTrim'),
			'validators' => array(
				new Zend_Validate_NotEmpty(),
				new Zend_Validate_EmailAddress()
			)
		))
		->addElement('password', 'password', array(
			'label'      => 'Password:',
			'filters'    => array('StringTrim'),
			'validators' => array(
				new Zend_Validate_NotEmpty(),
				new Zend_Validate_Alnum(),
				new Zend_Validate_StringLength( array(3,255) )
			)
		))
		->addElement('text', 'openid', array(
			'label'      => 'Open ID:',
			'filters'    => array('StringTrim'),
			'validators' => array(
				new Zend_Validate_NotEmpty(),
				new Zend_Validate_StringLength( array(3,255) )
			)
		))
		->addElement('submit', 'submit');
	}
}