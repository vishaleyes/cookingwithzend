<?php

class Forms_Resource extends Zend_Form
{

	public function init()
	{
		$roles = new Models_AclRole();
		$roles = $roles->table->fetchAll();
		
		$this->setAction('/comment/new');
		$this->addElement('text', 'name', array(
			'label'      => 'Resource:',
			'required'   => true,
			'filters'    => array('StringTrim'),
			'validators' => array(
				new Zend_Validate_NotEmpty(),
			)
		))->addElement('select', 'role_id', array(
		))
		->addElement('submit', 'submit');
	}
}