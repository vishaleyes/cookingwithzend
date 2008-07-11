<?php

class RecipeRow extends Zend_Db_Table_Row_Abstract {
	
	public function getIngredients()
	{
		$output = array();
		$ingredients = $this->findRecipeIngredient();
		foreach( $ingredients as $ingredient ) {
			$tmp = $ingredient->toArray();
			$tmp['name'] = $ingredient->findParentIngredient()->name;
			$output[] = $tmp;
		}

		return $output;
	}

}
