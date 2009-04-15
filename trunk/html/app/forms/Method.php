<?php

class Forms_Method extends Zend_Form
{

	public function init()
	{
		$this->addElement('textarea', 'description', array(
			'label'      => 'Description:',
			'required'   => true,
			'filters'    => array('StringTrim'),
			'validators' => array(
				new Zend_Validate_NotEmpty()
			)
		))
		->addElement('hidden', 'recipe_id')
		->addElement('submit', 'submit');
	}
}