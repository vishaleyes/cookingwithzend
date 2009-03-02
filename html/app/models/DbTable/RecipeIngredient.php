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
}
