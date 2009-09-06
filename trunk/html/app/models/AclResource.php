<?php

class Models_AclResource extends Models_GenericModel
{
	public function getResources()
	{
		$select = $this->db->select()
			->from('acl_resources')
			->joinLeft(
				'acl_roles', 
				'acl_roles.id = acl_resources.role_id', 
				array('role_name' => 'name')
			)
			->order('name asc')
			->order('role_id asc');
		$stmt = $this->db->query($select);
		$rowSet = $stmt->fetchAll();
		return $rowSet;
	}
}