<?php

class Models_Recipe extends Models_GenericModel
{
	
	public function getRecipe($recipe_id)
	{
		$select = $this->db->select()
			->from(array('r' => 'recipes'))
			->joinLeft(array('u' => 'users'), 'r.creator_id = u.id', array(
				'username' => 'u.name'
			))
			->where('r.id = ?', $recipe_id);
		$stmt = $this->db->query($select);
		$rowSet = $stmt->fetchAll();
		return $rowSet[0];
	}
	
	/**
	 * Return all the recipies with a username thrown in
	 *
	 * @param string $userID
	 * @param string $order
	 * @return array
	 */
	public function getRecipes($userID = null, $order = null, $direction = 'ASC')
	{
		$select = $this->db->select()
			->from(array('r' => 'recipes'))
			->joinLeft(array('u' => 'users'), 'r.creator_id = u.id', array(
				'username' => 'u.name'
			));
		
		if (null !== $userID)
			$select->where('u.name = ?', $userID);
		
		if (null !== $order)
		{
			$select->order("r.$order $direction");
			$select->order("r.name $direction");
		}
		else
			$select->order("r.name");
			
		$stmt = $this->db->query($select);
		$rowSet = $stmt->fetchAll();
		return $rowSet;
	}
	
	/**
	 * Rebuilds the ingredient from the joining tables
	 *
	 * @param int $id
	 * @return array
	 */
	
	public function getIngredients( $recipe_id )
	{
		$select = $this->db->select()
			->from(array('ri' => 'recipe_ingredients'))
			->joinLeft(
				array('i' => 'ingredients'), 
				'ri.ingredient_id = i.id', 
				array( 'name' => 'i.name')
			)
			->joinLeft(
				array('m' => 'measurements'),
				'ri.measurement_id = m.id',
				array( 'measurement' => 'm.abbreviation')
			)
			->where('ri.recipe_id = ?', $recipe_id);
		return $this->db->fetchAll($select);
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
