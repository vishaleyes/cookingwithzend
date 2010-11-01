<?php

class Models_Method extends Models_GenericModel implements Zend_Acl_Resource_Interface 
{
	protected $_ownerUserId = null;

	protected $_data = array();
	
	public function getResourceId()
	{
		return 'method';
	}

	/**
	 * Retrieve the methods for a recipe
	 *
	 * @param int $id
	 * @return array
	 */

	public function getMethods( $recipe_id )
	{
		$select = $this->db->select()
			->from(array('m' => 'method_items'))
			->where('m.recipe_id = ?', $recipe_id)
			->order('position')
			->order('id');
			
		return $this->db->fetchAll($select);

	}
}
