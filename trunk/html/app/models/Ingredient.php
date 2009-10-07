<?php

class Models_Ingredient extends Models_GenericModel
{
	public function getIngredientForRecipe( $recipe_id, $ingredient_id )
	{
		$select = $this->db->select()
			->from(array( 'ri' => 'recipe_ingredients'))
			->joinLeft(array('i' => 'ingredients'), 'i.id = ri.ingredient_id', 'i.name')
			->joinLeft(array('r' => 'recipes'), 'r.id = ri.recipe_id', 'r.creator_id')
			->joinLeft(array('m' => 'measurements'), 'm.id = ri.measurement_id', array('measurement' => 'm.name'))
			->where('ri.recipe_id = ?', $recipe_id)
			->where('ri.ingredient_id = ?', $ingredient_id);	
		
		return $this->db->fetchRow($select);	
	}
}
