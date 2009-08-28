<?php

class Recipe_Acl extends Zend_Acl {

	public function __construct() 
	{
		$db = Zend_Registry::get('db');
		
		$select = $db->select()
			->from(array('r'=>'acl_roles'))
			->joinLeft(array('i' => 'acl_roles'), 'i.id = r.inherit_id', array('inherit_name'=>'i.name'))
			->order('r.inherit_id ASC');
		print $select->__toString();
		$stmt = $db->query($select);
		$roles = $stmt->fetchAll();
		
		foreach ($roles as $role)
		{
			$inheritRole = null;
			if ($role['inherit_id'] !== null)
				$inheritRole =$role['inherit_name'];
			
			print_r($role);
			$this->addRole( new Zend_Acl_Role($role['name']), $role['inherit_name'] );
		}
		
		$select = $db->select()
			->from('acl_resources')
			->joinLeft('acl_roles', 'acl_roles.id = acl_resources.role_id', array('role_name' => 'name'));
		$stmt = $db->query($select);
		$resources = $stmt->fetchAll();
		
		foreach ($resources as $resource)
		{
			$this->add( new Zend_Acl_Resource( $resource['name'] ) );
			$this->allow( $resouce['role_name'], $resource['name'] );
		}	
		
		print_r($this);
		
	}
	
}