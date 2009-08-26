<?php

class Models_Comment extends Models_GenericModel
{
	/**
	 * Return all the comments with a username thrown in
	 *
	 * @param string $field
	 * @param string $order
	 * @return array
	 */
	public function getComments($field, $id, $limit = 10)
	{
		$select = $this->db->select()
			->from( array( 'c' => 'comments') )
			->joinLeft( array('u' => 'users'), 'c.user_id = u.id', array('username' => 'u.name','u.email') )
			->joinLeft( array('r' => 'recipes'), 'r.id = c.recipe_id', array('recipe' => 'r.name', 'recipe_id' => 'r.id') )				
			->where("$field = ?", $id)
			->order('c.created DESC')
			->limit($limit);
				
		$stmt = $this->db->query($select);
		$rowSet = $stmt->fetchAll();
		return $rowSet;
	}
	
}
