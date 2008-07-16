<?php

class RecipeIngredient extends Zend_Db_Table_Abstract {
	
	protected $_name = "recipe_ingredients";
	protected $_primary = array( "recips_id", "ingredient_id" );
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
	
	// Form elements for add/edit
	public $_form_fields_config = array(
		array( 'text', 'amount', array(
			'label' => 'Amount (e.g. 450g)',
			'validators' => array(
				array( 'alnum', true, true ),
				array( 'stringLength', false, array( 3, 255 ) ),
			)
		) ),
		array( 'text', 'quantity', array(
			'label' => 'Quantity (e.g. 2 Eggs)',
			'validators' => array(
				array( 'int' )
			)
		) )
	);

	// May be able to delete this
	function __construct( $prefetch = true )
	{
		if ( ! $prefetch === false ) unset( $_rowClass );
		$this->db = Zend_Registry::get("db");
		Zend_Db_Table_Abstract::setDefaultAdapter($this->db);
		
		$this->log = Zend_Registry::get('log');
		
		$this->_setup();
	}

}
