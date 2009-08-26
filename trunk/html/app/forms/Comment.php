<?php

class Forms_Comment extends Zend_Form
{

	public function init()
	{
		$this->setAction('/comment/new');
		$this->addElement('textarea', 'comment', array(
			'label'      => 'Comment:',
			'filters'    => array('StringTrim'),
			'validators' => array(
				new Zend_Validate_NotEmpty(),
			)
		))
		->addElement('hidden', 'recipe_id')
		->addElement('submit', 'submit');
	}
}