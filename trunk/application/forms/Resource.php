<?php

class Forms_Resource extends Zend_Form
{

	public function init()
	{
		$roles = new Models_AclRole();
		$roles = $roles->fetchForMultiSelect('id', 'name');
				
		$this->addElement('text', 'name', array(
			'label'      => 'Resource:',
			'required'   => true,
			'filters'    => array('StringTrim'),
			'validators' => array(
				new Zend_Validate_NotEmpty(),
			)
		))->addElement('select', 'role_id', array(
			'label'        => 'Role:',
			'required'     => true,
			'multiOptions' => $roles, 
			'validators'   => array(
				new Zend_Validate_NotEmpty(),
			)
		))
		->addElement('submit', 'submit');
	}
}