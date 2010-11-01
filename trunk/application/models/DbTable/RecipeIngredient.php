<?php

class Models_DbTable_RecipeIngredient extends Zend_Db_Table_Abstract {
	
	protected $_name = "recipe_ingredients";
	protected $_primary = array( "recipe_id", "ingredient_id" );
	protected $_rowClass = 'RecipeIngredientRow';

	# Primary does Auto Inc
	protected $_sequence = true;
	
	protected $_referenceMap = array(
		'Recipe' => array(
			'columns'		=> 'recipe_id',
			'refTableClass' => 'Recipe',
			'refColumns'	=> 'id'
		),
		'Ingredient' => array(
			'columns'		=> 'ingredient_id',
			'refTableClass' => 'Ingredient',
			'refColumns'	=> 'id'
		),
		'Measurement' => array(
			'columns'		=> 'measurement_id',
			'refTableClass' => 'Measurement',
			'refColumns'	=> 'id'
		)
	);
	
	/**
	 * Override the insert to try and throw an exception back if it fails
	 * @param $data array associative array of vars to go in the db
	 * @return $row Zend_Db_Table_Row the row of the Ingredient table
	 */

	function insert( array $data )
	{
		try {
			parent::insert( $data );
			$this->getDefaultAdapter()->update('recipes', 
				array('ingredients_count' => new Zend_Db_Expr('ingredients_count + 1'))
			);
		} catch (Exception $e) {
			throw new Zend_Db_Table_Exception($e->getMessage());
		}
	}
}
