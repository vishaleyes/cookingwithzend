<?php

class Models_Ingredient extends Models_GenericModel implements Zend_Acl_Resource_Interface
{
	protected $_ownerUserId = null;
	
	protected $_data = array();
	
	public function getResourceId()
	{
		return 'ingredient';
	}
	
	/**
	 * Gather the ingredients for a recipe
	 * 
	 * @param int $recipe_id
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
	 * Grab an ingredient for a recipe
	 *
	 * @param int $recipe_id
	 * @param int $ingredient_id
	 * @return array
	 */
	
	public function getIngredientForRecipe( $recipe_id, $ingredient_id )
	{
		$select = $this->db->select()
			->from(array( 'ri' => 'recipe_ingredients'))
			->joinLeft(array('i' => 'ingredients'), 'i.id = ri.ingredient_id', 'i.name')
			->joinLeft(array('r' => 'recipes'), 'r.id = ri.recipe_id', 'r.user_id')
			->joinLeft(array('m' => 'measurements'), 'm.id = ri.measurement_id', array('measurement' => 'm.name'))
			->where('ri.recipe_id = ?', $recipe_id)
			->where('ri.ingredient_id = ?', $ingredient_id);

		$row = $this->db->fetchRow($select);
		
		$this->dataMerge( $row );
		
		return $row;	
	}
}
