<?php

class RecipeIngredient extends Zend_Db_Table_Abstract {
	
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
     * This is to replace the _form_fields_config, I find it easier to follow - CL
     */

	public function getFormElements()
	{
		$elements = array();

		$e = new Zend_Form_Element( 'text' );
		$e->setName( 'amount')
          ->setLabel( 'Amount (e.g. 450g)' )
		  ->addValidator( new Zend_Validate_Int(), true )
          ->addValidator( new Zend_Validate_GreaterThan(0), true );
		$elements[] = $e;
		
		$e = new Zend_Form_Element( 'text' );
		$e->setName( 'quantity')
		  ->setLabel( 'Quantity (e.g. 2 Eggs)' )
		  ->addValidator( new Zend_Validate_Int(), true )
          ->addValidator( new Zend_Validate_GreaterThan(0), true );
		$elements[] = $e;

		return $elements;
	}

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
