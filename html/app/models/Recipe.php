<?php

class Models_Recipe extends Models_GenericModel
{
	
	/**
	 * Rebuilds the ingredient from the joining tables
	 *
	 * @param int $id
	 * @return array
	 */
	
	public function getIngredients( $id )
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
			->where('ri.recipe_id = ?', $id);
		return $this->db->fetchAll($select);
	}
	
	/**
	 * Retrieve the methods for a recipe
	 *
	 * @param int $id
	 * @return array
	 */
	
	public function getMethods( $id )
	{
		$select = $this->db->select()
			->from(array('m' => 'method_items'))
			->where('m.recipe_id = ?', $id);
			
		return $this->db->fetchAll($select);
	}
	
	/**
	 * Counts the ingredients for this recipe and returns the number
	 * 
	 * @param int $id Id number of the recipe
	 * @return int
	 */
	
	public function getIngredientsCount( $id )
	{
		return $this->__getCountFromDb('recipe_ingredients', $id);
	}
	
	/**
	 * Generic function to return a count from the relevant table and
	 * relevant field
	 *
	 * @todo Maybe move this to generic?
	 * @param string $table Name of the table
	 * @param int $id Id number of the recipe
	 * @return int
	 */
	
	private function __getCountFromDb( $table, $id )
	{
		$select = $this->db->select()
			->from($table, array( 'counter' => new Zend_Db_Expr('COUNT(*)')))
			->where('recipe_id = ?', $id);
		
		$count = $this->db->fetchCol($select);
		return $count[0];
	}
	
	
}
