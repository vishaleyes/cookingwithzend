<?php

/**
 * Builds the AclResource list using the database
 * @author chris
 *
 */

class Recipe_Acl extends Zend_Acl {

	public function __construct() 
	{
		$db = Zend_Registry::get('db');
		
		$select = $db->select()
			->from(array('r'=>'acl_roles'))
			->joinLeft(array('i' => 'acl_roles'), 'i.id = r.inherit_id', array('inherit_name'=>'i.name'))
			->order('r.inherit_id ASC');
		$stmt = $db->query($select);
		$roles = $stmt->fetchAll();
		
		foreach ($roles as $role)	
			$this->addRole( new Zend_Acl_Role($role['name']), $role['inherit_name'] );
		
		$r = new Models_AclResource();
		$resources = $r->getResources();
		
		foreach ($resources as $resource)
		{
			$this->add( new Zend_Acl_Resource( $resource['name'] ) );
			// if the privilege is edit or delete we want to add assertion
			if ( $resource['privilege'] == 'edit' || $resource['privilege'] == 'delete')
				$this->allow( $resource['role_name'], $resource['name'], $resource['privilege'], new Recipe_Acl_CanAmmendAssertion() );
			else
				$this->allow( $resource['role_name'], $resource['name'], $resource['privilege']);
		}		
	}
	
}